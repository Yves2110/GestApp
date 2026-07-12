<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Service;
use App\Models\Objective;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function activitiesReport(Request $request)
    {
        $user  = Auth::user();
        $query = Activity::with(['service', 'objective', 'underObjective', 'periode']);

        if ((int) $user->role_id === 4) {
            $query->where('service_id', $user->service_id);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('workflow_status')) {
            $query->where('workflow_status', $request->workflow_status);
        }

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        $activities = $query->orderBy('created_at', 'desc')->get();

        $stats = [
            'total'     => $activities->count(),
            'validated' => $activities->where('workflow_status', 'validated')->count(),
            'pending'   => $activities->where('workflow_status', 'pending')->count(),
            'draft'     => $activities->where('workflow_status', 'draft')->count(),
            'rejected'  => $activities->where('workflow_status', 'rejected')->count(),
            'budget'    => $activities->sum('price'),
        ];

        $services  = Service::withCount('activities')->orderByDesc('activities_count')->get();
        $byService = $activities->groupBy(fn($a) => $a->service->label ?? 'Sans service');

        $pdf = Pdf::loadView('pdf.activities-report', compact(
            'activities', 'stats', 'services', 'byService', 'request'
        ))
        ->setPaper('a4', 'landscape')
        ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'rapport-activites-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    public function performanceReport(Request $request)
    {
        $activities = Activity::with(['service', 'objective', 'periode'])
            ->when((int) Auth::user()->role_id === 4, fn($q) => $q->where('service_id', Auth::user()->service_id))
            ->get();

        $wfLabels = Activity::WORKFLOW_LABELS;

        $wfStats = collect(array_keys($wfLabels))->mapWithKeys(fn($k) => [
            $k => $activities->where('workflow_status', $k)->count()
        ]);

        $validationRate = $activities->count() > 0
            ? round($activities->where('workflow_status', 'validated')->count() / $activities->count() * 100, 1)
            : 0;

        $byService = Service::withCount([
            'activities as total_count',
            'activities as validated_count' => fn($q) => $q->where('workflow_status', 'validated'),
        ])->orderByDesc('total_count')->get();

        $pdf = Pdf::loadView('pdf.performance-report', compact(
            'activities', 'wfStats', 'wfLabels', 'validationRate', 'byService'
        ))
        ->setPaper('a4', 'portrait')
        ->setOption(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        return $pdf->download('rapport-performance-' . now()->format('Y-m-d') . '.pdf');
    }
}
