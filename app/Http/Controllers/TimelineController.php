<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Service;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('label')->get();
        $periodes = Periode::orderBy('id')->get();
        return view('pages.activites-timeline', compact('services', 'periodes'));
    }

    public function data(Request $request)
    {
        $user  = Auth::user();
        $query = Activity::with(['service', 'periode', 'objective']);

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

        $activities = $query->get();

        $wfColors = [
            'draft'     => '#6c757d',
            'pending'   => '#fd7e14',
            'validated' => '#198754',
            'rejected'  => '#dc3545',
        ];

        $grouped = $activities
            ->groupBy(fn($a) => $a->service->label ?? 'Sans service')
            ->map(fn($group, $serviceName) => [
                'service' => $serviceName,
                'items'   => $group->map(fn(Activity $a) => [
                    'id'             => $a->id,
                    'label'          => \Illuminate\Support\Str::limit($a->label, 50),
                    'start'          => $a->created_at->format('Y-m-d'),
                    'end'            => $a->updated_at->format('Y-m-d'),
                    'color'          => $wfColors[$a->workflow_status ?? 'draft'] ?? '#6c757d',
                    'workflow_status'=> $a->workflow_status ?? 'draft',
                    'periode'        => $a->periode->label ?? '—',
                    'objective'      => \Illuminate\Support\Str::limit($a->objective->label ?? '—', 40),
                    'url'            => route('activites.show', $a->id),
                ])->values(),
            ])->values();

        $minDate = $activities->min('created_at')?->format('Y-m-d') ?? now()->startOfYear()->format('Y-m-d');
        $maxDate = $activities->max('updated_at')?->format('Y-m-d') ?? now()->endOfYear()->format('Y-m-d');

        return response()->json([
            'groups'   => $grouped,
            'min_date' => $minDate,
            'max_date' => $maxDate,
        ]);
    }
}
