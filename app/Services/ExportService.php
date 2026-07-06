<?php

namespace App\Services;

use App\Models\Activities;
use App\Models\Objective;
use App\Models\service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportService
{
    /**
     * Export des activités en format CSV
     */
    public function exportActivitiesCSV($filters = [])
    {
        $query = Activities::with(['service', 'objective', 'under_objective', 'periode']);
        
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
        
        $csv = "ID,Libellé,Service,Objectif,Sous-Objectif,Période,Indicateur,Cible,Coût (€),Source de financement,Structure,Statut,Date de création\n";
        
        foreach ($activities as $activity) {
            $csv .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",%.2f,\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $activity->id,
                $activity->label,
                $activity->service->service ?? 'N/A',
                $activity->objective->label ?? 'N/A',
                $activity->under_objective->label ?? 'N/A',
                $activity->periode->label ?? 'N/A',
                $activity->indicator,
                $activity->target,
                $activity->price,
                $activity->source_of_funding,
                $activity->structure,
                $activity->status ? 'Active' : 'En attente',
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
        $query = Activities::with(['service', 'objective', 'under_objective', 'periode']);
        
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
                'total' => Activities::count(),
                'active' => Activities::where('status', 1)->count(),
                'pending' => Activities::where('status', 0)->count(),
                'total_budget' => Activities::sum('price'),
                'avg_budget' => Activities::avg('price'),
            ],
            'services' => [
                'total' => service::count(),
                'with_activities' => service::has('activities')->count(),
            ],
            'by_service' => service::withCount('activities')
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
        $monthlyEvolution = Activities::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                YEAR(created_at) as year,
                MONTH(created_at) as month_num,
                COUNT(*) as activities_count,
                SUM(price) as total_budget,
                AVG(price) as avg_budget,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_count
            ')
            ->where('created_at', '>=', $startDate)
            ->groupBy('month', 'year', 'month_num')
            ->orderBy('year')
            ->orderBy('month_num')
            ->get();
        
        // Performance par service
        $servicePerformance = service::selectRaw('
                services.id,
                services.service,
                COUNT(activities.id) as total_activities,
                SUM(activities.price) as total_budget,
                AVG(activities.price) as avg_budget,
                SUM(CASE WHEN activities.status = 1 THEN 1 ELSE 0 END) as active_activities,
                ROUND(SUM(CASE WHEN activities.status = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(activities.id), 2) as completion_rate
            ')
            ->leftJoin('activities', 'services.id', '=', 'activities.service_id')
            ->where('activities.created_at', '>=', $startDate)
            ->groupBy('services.id', 'services.service')
            ->orderBy('total_activities', 'desc')
            ->get();
        
        // Indicateurs de performance clés
        $kpi = [
            'period_months' => $months,
            'start_date' => $startDate->format('d/m/Y'),
            'end_date' => $endDate->format('d/m/Y'),
            'total_activities' => Activities::where('created_at', '>=', $startDate)->count(),
            'total_budget' => Activities::where('created_at', '>=', $startDate)->sum('price'),
            'completion_rate' => Activities::where('created_at', '>=', $startDate)
                ->where('status', 1)->count() / 
                max(Activities::where('created_at', '>=', $startDate)->count(), 1) * 100,
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
        $currentPeriod = Activities::whereBetween('created_at', [$startDate, $endDate])->sum('price');
        $previousPeriod = Activities::whereBetween('created_at', [
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
