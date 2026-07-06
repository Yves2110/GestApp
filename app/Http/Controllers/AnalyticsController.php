<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Objective;
use App\Models\under_objective;
use App\Models\service;
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
        // Statistiques générales
        $stats = [
            'total_objectives' => Objective::count(),
            'total_under_objectives' => under_objective::count(),
            'total_activities' => Activities::count(),
            'total_services' => service::count(),
            'total_users' => User::count(),
            'total_budget' => Activities::sum('price'),
            'active_activities' => Activities::where('status', 1)->count(),
            'pending_activities' => Activities::where('status', 0)->count(),
        ];

        // Données pour graphique des activités par service
        $activitiesByService = Activities::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.service as label, COUNT(*) as value, SUM(activities.price) as budget')
            ->groupBy('services.service', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        // Données pour graphique des objectifs par période
        $objectivesByPeriod = Activities::join('periodes', 'activities.periode_id', '=', 'periodes.id')
            ->selectRaw('periodes.label as period, COUNT(*) as activities, SUM(activities.price) as total_budget')
            ->groupBy('periodes.label', 'activities.periode_id')
            ->orderBy('period')
            ->get();

        // Évolution mensuelle des activités
        $monthlyActivities = Activities::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top 5 des services les plus actifs
        $topServices = Activities::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.service as name, COUNT(*) as activities, SUM(activities.price) as total_budget')
            ->groupBy('services.service', 'activities.service_id')
            ->orderBy('activities', 'desc')
            ->limit(5)
            ->get();

        // Répartition des statuts d'activités
        $statusDistribution = [
            'active' => Activities::where('status', 1)->count(),
            'pending' => Activities::where('status', 0)->count(),
        ];

        // Budget par service pour le graphique circulaire
        $budgetByService = Activities::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.service as name, SUM(activities.price) as value')
            ->groupBy('services.service', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        // Activités récentes avec relations
        $recentActivities = Activities::with(['service', 'objective', 'under_objective', 'periode'])
            ->latest()
            ->take(10)
            ->get();

        // Objectifs les plus performants
        $topObjectives = Objective::withCount('activities')
            ->withSum('activities', 'price')
            ->orderBy('activities_count', 'desc')
            ->limit(5)
            ->get();

        return view('pages.analytics', compact(
            'stats',
            'activitiesByService',
            'objectivesByPeriod',
            'monthlyActivities',
            'topServices',
            'statusDistribution',
            'budgetByService',
            'recentActivities',
            'topObjectives'
        ));
    }

    /**
     * API pour les données du graphique d'activités par service
     */
    public function getActivitiesByService()
    {
        $data = Activities::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.service as label, COUNT(*) as value')
            ->groupBy('services.service', 'activities.service_id')
            ->orderBy('value', 'desc')
            ->get();

        return response()->json($data);
    }

    /**
     * API pour les données du graphique d'évolution mensuelle
     */
    public function getMonthlyEvolution()
    {
        $data = Activities::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
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
        $data = Activities::join('services', 'activities.service_id', '=', 'services.id')
            ->selectRaw('services.service as name, SUM(activities.price) as value')
            ->groupBy('services.service', 'activities.service_id')
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
        $activities = Activities::with(['service', 'objective', 'under_objective', 'periode'])
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
        $service = service::findOrFail($serviceId);
        
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
     * Rapport de performance global
     */
    public function performanceReport()
    {
        // Période de analyse (derniers 6 mois)
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        // Taux de complétion des activités
        $completionRate = Activities::where('created_at', '>=', $startDate)
            ->where('status', 1)
            ->count() / Activities::where('created_at', '>=', $startDate)->count() * 100;

        // Budget moyen par activité
        $avgBudgetPerActivity = Activities::where('created_at', '>=', $startDate)
            ->avg('price');

        // Croissance des activités
        $previousPeriodActivities = Activities::whereBetween('created_at', [
            $startDate->copy()->subMonths(6),
            $startDate
        ])->count();

        $currentPeriodActivities = Activities::whereBetween('created_at', [
            $startDate,
            $endDate
        ])->count();

        $growthRate = $previousPeriodActivities > 0 
            ? (($currentPeriodActivities - $previousPeriodActivities) / $previousPeriodActivities) * 100 
            : 0;

        $report = [
            'period' => [
                'start' => $startDate->format('d/m/Y'),
                'end' => $endDate->format('d/m/Y'),
                'months' => 6
            ],
            'completion_rate' => round($completionRate, 2),
            'avg_budget_per_activity' => round($avgBudgetPerActivity, 2),
            'growth_rate' => round($growthRate, 2),
            'total_activities_current' => $currentPeriodActivities,
            'total_activities_previous' => $previousPeriodActivities,
        ];

        return response()->json($report);
    }
}
