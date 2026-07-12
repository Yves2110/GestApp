<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Service;
use App\Models\Periode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportService
{
    /**
     * Export des activités en format CSV
     */
    public function exportActivitiesCSV($filters = [])
    {
        $query = Activity::with(['service', 'objective', 'underObjective', 'periode']);
        
        // Appliquer les filtres
        if (!empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['workflow_status'])) {
            $query->where('workflow_status', $filters['workflow_status']);
        }

        $activities = $query->get();

        $wfLabels = Activity::WORKFLOW_LABELS;

        $csv = "ID,Libellé,Service,Objectif,Sous-Objectif,Période,Indicateur,Cible,Coût (FCFA),Source de financement,Structure,Statut,Workflow,Soumis le,Validé le,Date de création\n";

        foreach ($activities as $activity) {
            $csv .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%s,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $activity->id,
                str_replace('"', '""', $activity->label),
                str_replace('"', '""', $activity->service->label ?? 'N/A'),
                str_replace('"', '""', $activity->objective->label ?? 'N/A'),
                str_replace('"', '""', $activity->underObjective->label ?? 'N/A'),
                str_replace('"', '""', $activity->periode->label ?? 'N/A'),
                str_replace('"', '""', $activity->indicator ?? ''),
                str_replace('"', '""', $activity->target ?? ''),
                number_format($activity->price ?? 0, 2, '.', ''),
                str_replace('"', '""', $activity->source_of_funding ?? ''),
                str_replace('"', '""', $activity->structure ?? ''),
                $activity->status ? 'Active' : 'Inactive',
                $wfLabels[$activity->workflow_status] ?? $activity->workflow_status,
                $activity->submitted_at?->format('d/m/Y H:i') ?? '',
                $activity->validated_at?->format('d/m/Y H:i') ?? '',
                $activity->created_at->format('d/m/Y H:i')
            );
        }
        
        return $csv;
    }
    
    /**
     * Génération du rapport d'activités en format array pour PDF
     */
    public function generateActivitiesReport($filters = [])
    {
        $query = Activity::with(['service', 'objective', 'underObjective', 'periode']);
        
        // Appliquer les filtres
        if (!empty($filters['service_id'])) {
            $query->where('service_id', $filters['service_id']);
        }
        
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        $activities = $query->get();
        
        // Statistiques
        $stats = [
            'total_activities' => $activities->count(),
            'total_budget' => $activities->sum('price'),
            'active_activities' => $activities->where('status', 1)->count(),
            'pending_activities' => $activities->where('status', 0)->count(),
            'avg_budget_per_activity' => $activities->avg('price'),
            'services_count' => $activities->pluck('service_id')->unique()->count(),
        ];
        
        // Données par service
        $byService = $activities->groupBy('service.service')->map(function ($activities, $service) {
            return [
                'service' => $service,
                'count' => $activities->count(),
                'budget' => $activities->sum('price'),
                'active' => $activities->where('status', 1)->count(),
            ];
        })->values();
        
        // Données par période
        $byPeriod = $activities->groupBy('periode.label')->map(function ($activities, $period) {
            return [
                'period' => $period,
                'count' => $activities->count(),
                'budget' => $activities->sum('price'),
            ];
        })->values();
        
        return [
            'stats' => $stats,
            'activities' => $activities,
            'by_service' => $byService,
            'by_period' => $byPeriod,
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'filters' => $filters,
        ];
    }
    
    /**
     * Export des statistiques globales
     */
    public function exportGlobalStats()
    {
        $stats = [
            'generated_at' => now()->format('d/m/Y H:i:s'),
            'period' => 'Toutes données',
            'objectives' => [
                'total' => Objective::count(),
                'with_activities' => Objective::has('activities')->count(),
            ],
            'activities' => [
                'total' => Activity::count(),
                'active' => Activity::where('status', 1)->count(),
                'pending' => Activity::where('status', 0)->count(),
                'total_budget' => Activity::sum('price'),
                'avg_budget' => Activity::avg('price'),
            ],
            'services' => [
                'total' => Service::count(),
                'with_activities' => Service::has('activities')->count(),
            ],
            'by_service' => Service::withCount('activities')
                ->withSum('activities', 'price')
                ->orderBy('activities_count', 'desc')
                ->get()
                ->map(function ($service) {
                    return [
                        'name' => $service->service,
                        'activities_count' => $service->activities_count,
                        'total_budget' => $service->activities_sum_price ?? 0,
                        'avg_budget' => $service->activities_count > 0 
                            ? ($service->activities_sum_price ?? 0) / $service->activities_count 
                            : 0,
                    ];
                }),
        ];
        
        return $stats;
    }
    
    /**
     * Génération de données pour le rapport de performance
     */
    public function generatePerformanceReport($months = 6)
    {
        $startDate = Carbon::now()->subMonths($months);
        $endDate = Carbon::now();
        
        // Évolution mensuelle
        $driver = DB::getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', created_at) as month, strftime('%Y', created_at) as year, strftime('%m', created_at) as month_num"
            : "DATE_FORMAT(created_at, '%Y-%m') as month, YEAR(created_at) as year, MONTH(created_at) as month_num";

        $monthlyEvolution = Activity::selectRaw("
                {$monthExpr},
                COUNT(*) as activities_count,
                COALESCE(SUM(price),0) as total_budget,
                COALESCE(AVG(price),0) as avg_budget,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_count
            ")
            ->where('created_at', '>=', $startDate)
            ->groupBy('month', 'year', 'month_num')
            ->orderBy('year')
            ->orderBy('month_num')
            ->get();
        
        // Performance par service
        $servicePerformance = Service::selectRaw('
                services.id,
                services.label as service,
                COUNT(activities.id) as total_activities,
                SUM(activities.price) as total_budget,
                AVG(activities.price) as avg_budget,
                SUM(CASE WHEN activities.status = 1 THEN 1 ELSE 0 END) as active_activities,
                ROUND(SUM(CASE WHEN activities.status = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(activities.id), 2) as completion_rate
            ')
            ->leftJoin('activities', 'services.id', '=', 'activities.service_id')
            ->where('activities.created_at', '>=', $startDate)
            ->groupBy('services.id', 'services.label')
            ->orderBy('total_activities', 'desc')
            ->get();
        
        // Indicateurs de performance clés
        $kpi = [
            'period_months' => $months,
            'start_date' => $startDate->format('d/m/Y'),
            'end_date' => $endDate->format('d/m/Y'),
            'total_activities' => Activity::where('created_at', '>=', $startDate)->count(),
            'total_budget' => Activity::where('created_at', '>=', $startDate)->sum('price'),
            'completion_rate' => Activity::where('created_at', '>=', $startDate)
                ->where('status', 1)->count() /
                max(Activity::where('created_at', '>=', $startDate)->count(), 1) * 100,
            'avg_monthly_activities' => $monthlyEvolution->avg('activities_count'),
            'budget_growth' => $this->calculateBudgetGrowth($startDate, $endDate),
        ];
        
        return [
            'kpi' => $kpi,
            'monthly_evolution' => $monthlyEvolution,
            'service_performance' => $servicePerformance,
            'generated_at' => now()->format('d/m/Y H:i:s'),
        ];
    }
    
    /**
     * Calcul de la croissance du budget
     */
    private function calculateBudgetGrowth($startDate, $endDate)
    {
        $currentPeriod = Activity::whereBetween('created_at', [$startDate, $endDate])->sum('price');
        $previousPeriod = Activity::whereBetween('created_at', [
            $startDate->copy()->subMonths($endDate->diffInMonths($startDate)),
            $startDate
        ])->sum('price');
        
        if ($previousPeriod == 0) return 0;
        
        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 2);
    }
    
    /**
     * Préparation des données pour l'export Excel
     */
    public function prepareExcelData($type = 'activities', $filters = [])
    {
        switch ($type) {
            case 'activities':
                return $this->generateActivitiesReport($filters);
            case 'global_stats':
                return $this->exportGlobalStats();
            case 'performance':
                return $this->generatePerformanceReport();
            default:
                return $this->generateActivitiesReport($filters);
        }
    }
    
    /**
     * Nom de fichier pour l'export
     */
    public function generateFileName($type, $extension = 'csv')
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        
        switch ($type) {
            case 'activities':
                return "activites_universitaires_{$timestamp}.{$extension}";
            case 'global_stats':
                return "statistiques_globales_{$timestamp}.{$extension}";
            case 'performance':
                return "rapport_performance_{$timestamp}.{$extension}";
            default:
                return "export_gestapp_{$timestamp}.{$extension}";
        }
    }
}
