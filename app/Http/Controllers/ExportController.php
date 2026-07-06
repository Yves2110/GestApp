<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    protected $exportService;
    
    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }
    
    /**
     * Export des activités en CSV
     */
    public function exportActivitiesCSV(Request $request)
    {
        $filters = $request->only(['service_id', 'status', 'date_from', 'date_to']);
        $csv = $this->exportService->exportActivitiesCSV($filters);
        $filename = $this->exportService->generateFileName('activities', 'csv');
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * Export des statistiques globales en CSV
     */
    public function exportGlobalStatsCSV()
    {
        $stats = $this->exportService->exportGlobalStats();
        
        $csv = "Rapport Statistiques Globales - GestApp\n";
        $csv .= "Généré le: " . $stats['generated_at'] . "\n\n";
        
        $csv .= "Objectifs\n";
        $csv .= "Total," . $stats['objectives']['total'] . "\n";
        $csv .= "Avec activités," . $stats['objectives']['with_activities'] . "\n\n";
        
        $csv .= "Activités\n";
        $csv .= "Total," . $stats['activities']['total'] . "\n";
        $csv .= "Actives," . $stats['activities']['active'] . "\n";
        $csv .= "En attente," . $stats['activities']['pending'] . "\n";
        $csv .= "Budget total," . $stats['activities']['total_budget'] . " €\n";
        $csv .= "Budget moyen," . number_format($stats['activities']['avg_budget'], 2, ',', ' ') . " €\n\n";
        
        $csv .= "Services\n";
        $csv .= "Total," . $stats['services']['total'] . "\n";
        $csv .= "Avec activités," . $stats['services']['with_activities'] . "\n\n";
        
        $csv .= "Détail par Service\n";
        $csv .= "Service,Nombre d'activités,Budget total,Budget moyen\n";
        foreach ($stats['by_service'] as $service) {
            $csv .= sprintf(
                "\"%s\",%d,%.2f,%.2f\n",
                $service['name'],
                $service['activities_count'],
                $service['total_budget'],
                $service['avg_budget']
            );
        }
        
        $filename = $this->exportService->generateFileName('global_stats', 'csv');
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * Export du rapport de performance en CSV
     */
    public function exportPerformanceCSV()
    {
        $report = $this->exportService->generatePerformanceReport();
        
        $csv = "Rapport de Performance - GestApp\n";
        $csv .= "Généré le: " . $report['generated_at'] . "\n";
        $csv .= "Période: " . $report['kpi']['start_date'] . " au " . $report['kpi']['end_date'] . "\n\n";
        
        $csv .= "Indicateurs Clés\n";
        $csv .= "Période (mois)," . $report['kpi']['period_months'] . "\n";
        $csv .= "Total activités," . $report['kpi']['total_activities'] . "\n";
        $csv .= "Budget total," . $report['kpi']['total_budget'] . " €\n";
        $csv .= "Taux de complétion," . number_format($report['kpi']['completion_rate'], 2, ',', ' ') . " %\n";
        $csv .= "Moyenne mensuelle," . number_format($report['kpi']['avg_monthly_activities'], 2, ',', ' ') . " activités\n";
        $csv .= "Croissance budget," . $report['kpi']['budget_growth'] . " %\n\n";
        
        $csv .= "Évolution Mensuelle\n";
        $csv .= "Mois,Activités,Budget total,Budget moyen,Actives\n";
        foreach ($report['monthly_evolution'] as $month) {
            $csv .= sprintf(
                "%s,%d,%.2f,%.2f,%d\n",
                $month['month'],
                $month['activities_count'],
                $month['total_budget'],
                $month['avg_budget'],
                $month['active_count']
            );
        }
        
        $csv .= "\nPerformance par Service\n";
        $csv .= "Service,Total activités,Budget total,Budget moyen,Taux complétion\n";
        foreach ($report['service_performance'] as $service) {
            $csv .= sprintf(
                "\"%s\",%d,%.2f,%.2f,%.2f\n",
                $service->service,
                $service->total_activities,
                $service->total_budget,
                $service->avg_budget,
                $service->completion_rate
            );
        }
        
        $filename = $this->exportService->generateFileName('performance', 'csv');
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * API pour obtenir les données d'export en JSON
     */
    public function getExportData(Request $request)
    {
        $type = $request->get('type', 'activities');
        $filters = $request->only(['service_id', 'status', 'date_from', 'date_to']);
        
        $data = $this->exportService->prepareExcelData($type, $filters);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'filename' => $this->exportService->generateFileName($type, 'json'),
        ]);
    }
    
    /**
     * Page de configuration d'export
     */
    public function exportConfig()
    {
        $services = \App\Models\service::all();
        
        return view('pages.export-config', compact('services'));
    }
    
    /**
     * Traitement de l'export avec filtres
     */
    public function processExport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:activities,global_stats,performance',
            'format' => 'required|in:csv,json',
            'service_id' => 'nullable|exists:services,id',
            'status' => 'nullable|in:0,1',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);
        
        $type = $request->get('type');
        $format = $request->get('format');
        $filters = $request->only(['service_id', 'status', 'date_from', 'date_to']);
        
        switch ($type) {
            case 'activities':
                if ($format === 'csv') {
                    return $this->exportActivitiesCSV($request);
                }
                break;
            case 'global_stats':
                if ($format === 'csv') {
                    return $this->exportGlobalStatsCSV();
                }
                break;
            case 'performance':
                if ($format === 'csv') {
                    return $this->exportPerformanceCSV();
                }
                break;
        }
        
        // Pour le format JSON, retourner les données
        $data = $this->exportService->prepareExcelData($type, $filters);
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Données prêtes pour le téléchargement',
        ]);
    }
}
