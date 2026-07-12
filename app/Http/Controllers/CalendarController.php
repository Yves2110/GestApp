<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('label')->get();
        return view('pages.activites-calendar', compact('services'));
    }

    public function events(Request $request)
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

        $activities = $query->get();

        $colors = [
            'draft'     => ['bg' => '#6c757d', 'border' => '#5a6268'],
            'pending'   => ['bg' => '#fd7e14', 'border' => '#e8690b'],
            'validated' => ['bg' => '#198754', 'border' => '#146c43'],
            'rejected'  => ['bg' => '#dc3545', 'border' => '#b02a37'],
        ];

        $events = $activities->map(function (Activity $a) use ($colors) {
            $wf     = $a->workflow_status ?? 'draft';
            $color  = $colors[$wf] ?? $colors['draft'];
            $start  = $a->created_at->format('Y-m-d');
            $end    = $a->updated_at->format('Y-m-d');

            return [
                'id'              => $a->id,
                'title'           => \Illuminate\Support\Str::limit($a->label, 40),
                'start'           => $start,
                'end'             => $end !== $start ? $end : null,
                'backgroundColor' => $color['bg'],
                'borderColor'     => $color['border'],
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'service'          => $a->service->label ?? '—',
                    'objective'        => \Illuminate\Support\Str::limit($a->objective->label ?? '—', 40),
                    'periode'          => $a->periode->label ?? '—',
                    'workflow_status'  => $wf,
                    'url'              => route('activites.show', $a->id),
                ],
            ];
        });

        return response()->json($events);
    }
}
