<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use App\Models\service;
use App\Models\under_objective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function index()

    {
        $services=service::all();
        return view('pages.objective' ,compact('services'));
    }

    public function under_index()

    {
        $services=service::all();
        $objectives=Objective::all();
        return view('pages.under_objective' ,compact('services','objectives'));
    }

    public function ObjectiveStore(Request $request)
    {
        request()->validate([
            'label' => 'required'
        ]);
        Objective::create ([
            "role_id" =>3,
            "label" =>$request->label,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');

    }


    public function UnderObjectiveStore(Request $request)
    {
        request()->validate([
            'label' => 'required'
        ]);
        under_objective::create ([
            "objective_id" =>$request->objective_id,
            "label" =>$request->label,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');

    }
}
