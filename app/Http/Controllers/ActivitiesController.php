<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Objective;
use App\Models\Periode;
use App\Models\service;
use App\Models\under_objective;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()

    {
        // $services=Activities::all();
        $objectives=Objective::all();
        $underobjectives=under_objective::all();
        $services=service::all();
        $activities=Activities::paginate(10);
        $trimestres=Periode::all();
        return view('pages.activites' ,compact('services', 'objectives', 'underobjectives', 'activities', 'trimestres'));
    }

    public function ActivitiesStore(Request $request)

    {
    //   request()->validate([
    //     'service_id' => 'required|',
    //     'objective_id' => 'required|',
    //     'under_objective_id' => 'required|',
    //     'periode_id' => 'required|',
    //     'label' => 'required|string',
    //     'indicator' => 'required|string',
    //     'target' => 'required|string',
    //     'price' => 'required|integer',
    //     'source_of_funding' => 'required|string',
    //     'structure' => 'required|string',
    //     'status' => 'required|',
    //     'commentary' => 'required|string',
    //   ]);

        Activities::create([
            'service_id' => $request->service_id,
            'objective_id' => $request->objective_id,
            'under_objective_id' => $request->under_objective_id,
            'periode_id' => $request->periode_id,
            'label' => $request->label,
            'indicator' => $request->indicator,
            'target'=>  $request->target,
            'price'=>  $request->price,
            'source_of_funding'=>  $request->source_of_funding,
            'structure'=>  $request->structure,
            'status' => 0,
            'commentary' => $request->commentary,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }
}
