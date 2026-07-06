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
        $objectives=Objective::with(['under_objectives'])->get();
        $underobjectives=under_objective::with('objective')->get();
        $services=service::all();
        $activities=Activities::with(['service', 'objective', 'under_objective', 'periode'])->paginate(10);
        $trimestres=Periode::all();
        return view('pages.activites-modern' ,compact('services', 'objectives', 'underobjectives', 'activities', 'trimestres'));
    }

    public function ActivitiesStore(Request $request)

    {
        request()->validate([
            'service_id' => 'required|exists:services,id',
            'objective_id' => 'required|exists:objectives,id',
            'under_objective_id' => 'required|exists:under_objectives,id',
            'periode_id' => 'required|exists:periodes,id',
            'label' => 'required|string|max:255',
            'indicator' => 'required|string|max:255',
            'target' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'source_of_funding' => 'required|string|max:255',
            'structure' => 'required|string|max:255',
            'commentary' => 'nullable|string|max:5000',
        ]);

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
