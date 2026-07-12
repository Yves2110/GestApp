<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q      = trim($request->input('q', ''));
        $user   = Auth::user();
        $roleId = (int) $user->role_id;

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        // Activités
        $actQuery = Activity::with('service')
            ->where('label', 'like', "%{$q}%")
            ->limit(6);

        if ($roleId === 4) {
            $actQuery->where('service_id', $user->service_id);
        }

        foreach ($actQuery->get() as $a) {
            $results[] = [
                'type'     => 'activity',
                'icon'     => 'bi-lightning-charge',
                'title'    => Str::limit($a->label, 60),
                'subtitle' => $a->service->label ?? null,
                'badge'    => $a->workflowLabel,
                'url'      => route('activites.show', $a->id),
            ];
        }

        // Objectifs (admin+)
        if ($roleId <= 3) {
            foreach (Objective::where('label', 'like', "%{$q}%")->limit(4)->get() as $o) {
                $results[] = [
                    'type'  => 'objective',
                    'icon'  => 'bi-bullseye',
                    'title' => Str::limit($o->label, 60),
                    'url'   => route('Objective'),
                ];
            }

            // Services
            foreach (Service::where('label', 'like', "%{$q}%")->limit(3)->get() as $s) {
                $results[] = [
                    'type'  => 'service',
                    'icon'  => 'bi-building',
                    'title' => $s->label,
                    'url'   => route('Activites', ['service_id' => $s->id]),
                ];
            }
        }

        return response()->json($results);
    }
}
