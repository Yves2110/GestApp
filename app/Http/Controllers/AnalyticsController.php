<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\UnderObjective;
use App\Models\Service;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Affiche le dashboard analytique
     */
    public function index()
    {
        $driver = DB::getDriverName();

        // KPIs généraux — une seule requête agrégée sur activities
        $activityAgg = DB::table('activities')
            ->selectRaw('
                COUNT(*) as total_activities,
                COALESCE(SUM(price), 0) as total_budget,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_activities,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending_activities
            ')
            ->first();

        $stats = [
            'total_objectives'       => Objective::count(),
            'total_under_objectives' => UnderObjective::count(),
            'total_activities'       => (int) $activityAgg->total_activities,
            'total_services'         => Service::count(),
            'total_users'            => User::count(),
            'total_budget'           => (int) $activityAgg->total_budget,
            'active_activities'      => (int) $activityAgg->active_activities,
            'pending_activities'     => (int) $activityAgg->pending_activities,
        ];

        // KPIs workflow — une seule requête GROUP BY
        $wfRows = DB::table('activities')
            ->selectRaw('workflow_status, COUNT(*) as total')
            ->groupBy('workflow_status')
            ->pluck('total', 'workflow_status');

        $workflowStats = [
            Activity::WF_DRAFT     => (int) ($wfRows[Activity::WF_DRAFT]     ?? 0),
            Activity::WF_PENDING   => (int) ($wfRows[Activity::WF_PENDING]   ?? 0),
            Activity::WF_VALIDATED => (int) ($wfRows[Activity::WF_VALIDATED] ?? 0),
            Activity::WF_REJECTED  => (int) ($wfRows[Activity::WF_REJECTED]  ?? 0),
        ];

        // Activités par service
        $activitiesByService = Activity::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.label as label, COUNT(*) as value, COALESCE(SUM(activities.price),0) as budget')
            ->groupBy('services.label', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        // Activités par période
        $objectivesByPeriod = Activity::join('periodes', 'activities.periode_id', '=', 'periodes.id')
            ->selectRaw('periodes.label as period, COUNT(*) as activities, COALESCE(SUM(activities.price),0) as total_budget')
            ->groupBy('periodes.label', 'activities.periode_id')
            ->orderBy('activities', 'desc')
            ->get();

        // Évolution mensuelle (compatible MySQL & SQLite)
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at) as month"
            : "DATE_FORMAT(created_at, '%Y-%m') as month";

        $monthlyActivities = Activity::selectRaw("{$monthExpr}, COUNT(*) as count")
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 5 services
        $topServices = Activity::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.label as name, COUNT(*) as activities, COALESCE(SUM(activities.price),0) as total_budget')
            ->groupBy('services.label', 'activities.service_id')
            ->orderBy('activities', 'desc')
            ->limit(5)
            ->get();

        // Budget par service
        $budgetByService = Activity::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.label as name, COALESCE(SUM(activities.price),0) as value')
            ->groupBy('services.label', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        // Activités récentes
        $recentActivities = Activity::with(['service', 'objective', 'underObjective', 'periode'])
            ->latest()
            ->take(10)
            ->get();

        // Top objectifs
        $topObjectives = Objective::withCount('activities')
            ->withSum('activities', 'price')
            ->orderBy('activities_count', 'desc')
            ->limit(5)
            ->get();

        // Taux de validation
        $validationRate = $stats['total_activities'] > 0
            ? round($workflowStats[Activity::WF_VALIDATED] / $stats['total_activities'] * 100, 1)
            : 0;

        return view('pages.analytics', compact(
            'stats', 'workflowStats', 'validationRate',
            'activitiesByService', 'objectivesByPeriod', 'monthlyActivities',
            'topServices', 'budgetByService', 'recentActivities', 'topObjectives'
        ));
    }

    /**
     * API pour les données du graphique d'activités par service
     */
    public function getActivitiesByService()
    {
        $data = Activity::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.label as label, COUNT(*) as value')
            ->groupBy('services.label', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        return response()->json($data);
    }

    /**
     * API pour les données du graphique d'évolution mensuelle (MySQL + SQLite)
     */
    public function getMonthlyEvolution()
    {
        $driver    = DB::getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at) as month"
            : "DATE_FORMAT(created_at, '%Y-%m') as month";

        $data = Activity::selectRaw("{$monthExpr}, COUNT(*) as count")
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }

    /**
     * API pour les données du budget par service
     */
    public function getBudgetByService()
    {
        $data = Activity::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.label as name, SUM(activities.price) as value')
            ->groupBy('services.label', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        return response()->json($data);
    }

    /**
     * Export des statistiques en Excel
     */
    public function exportExcel()
    {
        // Préparation des données pour l'export
        $activities = Activity::with(['service', 'objective', 'underObjective', 'periode'])
            ->get()
            ->map(function ($activity) {
                return [
                    'ID' => $activity->id,
                    'Libellé' => $activity->label,
                    'Service' => $activity->service->service ?? 'N/A',
                    'Objectif' => $activity->objective->label ?? 'N/A',
                    'Sous-Objectif' => $activity->under_objective->label ?? 'N/A',
                    'Période' => $activity->periode->label ?? 'N/A',
                    'Indicateur' => $activity->indicator,
                    'Cible' => $activity->target,
                    'Coût (€)' => number_format($activity->price, 2, ',', ' '),
                    'Source de financement' => $activity->source_of_funding,
                    'Structure' => $activity->structure,
                    'Statut' => $activity->status ? 'Active' : 'En attente',
                    'Date de création' => $activity->created_at->format('d/m/Y H:i'),
                ];
            });

        // Pour l'instant, retournons les données en JSON
        // Dans une implémentation complète, on utiliserait Laravel Excel
        return response()->json([
            'data' => $activities,
            'exported_at' => now()->format('d/m/Y H:i:s'),
            'total_records' => $activities->count()
        ]);
    }

    /**
     * Statistiques détaillées par service
     */
    public function getServiceStats($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        
        $stats = [
            'service' => $service->service,
            'total_activities' => $service->activities()->count(),
            'total_budget' => $service->activities()->sum('price'),
            'active_activities' => $service->activities()->where('status', 1)->count(),
            'pending_activities' => $service->activities()->where('status', 0)->count(),
            'objectives_count' => $service->activities()->distinct('objective_id')->count('objective_id'),
        ];

        $activities = $service->activities()
            ->with(['objective', 'under_objective', 'periode'])
            ->latest()
            ->paginate(10);

        return view('pages.service-analytics', compact('stats', 'activities', 'service'));
    }

    /**
     * Rapport de performance global — page Blade
     */
    public function performanceReport()
    {
        $driver    = DB::getDriverName();
        $startDate = Carbon::now()->subMonths(6);
        $prevStart = Carbon::now()->subMonths(12);
        $endDate   = Carbon::now();

        // KPIs période courante
        $current = Activity::where('created_at', '>=', $startDate);
        $prev    = Activity::whereBetween('created_at', [$prevStart, $startDate]);

        $totalCurrent  = (clone $current)->count();
        $totalPrev     = (clone $prev)->count();
        $validatedCurrent = (clone $current)->where('workflow_status', Activity::WF_VALIDATED)->count();
        $budgetCurrent = (clone $current)->sum('price') ?? 0;
        $budgetPrev    = (clone $prev)->sum('price') ?? 0;

        $validationRate = $totalCurrent > 0
            ? round($validatedCurrent / $totalCurrent * 100, 1) : 0;
        $growthRate = $totalPrev > 0
            ? round(($totalCurrent - $totalPrev) / $totalPrev * 100, 1) : 0;
        $budgetGrowth = $budgetPrev > 0
            ? round(($budgetCurrent - $budgetPrev) / $budgetPrev * 100, 1) : 0;
        $avgBudget = $totalCurrent > 0
            ? round($budgetCurrent / $totalCurrent, 0) : 0;

        // Évolution mensuelle 6 mois
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at) as month"
            : "DATE_FORMAT(created_at, '%Y-%m') as month";

        $monthlyEvolution = Activity::selectRaw("
                {$monthExpr},
                COUNT(*) as total,
                SUM(CASE WHEN workflow_status = 'validated' THEN 1 ELSE 0 END) as validated,
                SUM(CASE WHEN workflow_status = 'pending'   THEN 1 ELSE 0 END) as pending,
                COALESCE(SUM(price), 0) as budget
            ")
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Performance par service (top 10)
        $servicePerf = Service::join('activities', 'services.id', '=', 'activities.service_id')
            ->selectRaw("
                services.id,
                services.label as name,
                COUNT(activities.id) as total,
                SUM(CASE WHEN activities.workflow_status = 'validated' THEN 1 ELSE 0 END) as validated,
                COALESCE(SUM(activities.price), 0) as budget
            ")
            ->where('activities.created_at', '>=', $startDate)
            ->groupBy('services.id', 'services.label')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($s) {
                $s->rate = $s->total > 0 ? round($s->validated / $s->total * 100) : 0;
                return $s;
            });

        // Workflow breakdown période courante
        $workflowBreakdown = [
            Activity::WF_DRAFT     => (clone $current)->where('workflow_status', Activity::WF_DRAFT)->count(),
            Activity::WF_PENDING   => (clone $current)->where('workflow_status', Activity::WF_PENDING)->count(),
            Activity::WF_VALIDATED => $validatedCurrent,
            Activity::WF_REJECTED  => (clone $current)->where('workflow_status', Activity::WF_REJECTED)->count(),
        ];

        return view('pages.rapport-performance', compact(
            'startDate', 'endDate',
            'totalCurrent', 'totalPrev',
            'validationRate', 'growthRate', 'budgetGrowth', 'avgBudget', 'budgetCurrent',
            'monthlyEvolution', 'servicePerf', 'workflowBreakdown'
        ));
    }
}
