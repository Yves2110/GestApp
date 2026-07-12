<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Objective;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $actQuery = Activity::query();
        if ((int) $user->role_id === 4) {
            $actQuery->where('service_id', $user->service_id);
        }

        $kpis = [
            'total'   => (clone $actQuery)->count(),
            'active'  => (clone $actQuery)->where('status', 1)->count(),
            'pending' => (clone $actQuery)->where('status', 0)->count(),
            'budget'  => (clone $actQuery)->sum('price'),
        ];

        $recentActivities = (clone $actQuery)
            ->with(['service', 'objective', 'periode'])
            ->latest()
            ->limit(5)
            ->get();

        $services = Service::withCount('activities')->get();
        $objectives = Objective::withCount('activities')->limit(5)->get();

        return view('pages.dashboard', compact('kpis', 'recentActivities', 'services', 'objectives'));
    }

    public function serviceajout()
    {
        $services = Service::all();
        return view('pages.serviceajout', ['services' => $services]);
    }

    public function servicestore(Request $request)
    {
        $request->validate([
            'service' => 'required|string|max:255',
        ]);
        Service::create([
            'label' => $request->service,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');

    }
}
