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
        $services = service::all();
        $objectives = Objective::paginate(5);
        return view('pages.objective', compact('services', 'objectives'));
    }

    public function under_index()

    {
        $services = service::all();
        $objectives = Objective::all();
        $under_objectives = under_objective::all();
        return view('pages.under_objective', compact('services', 'objectives', 'under_objectives'));
    }

    public function ObjectiveStore(Request $request)
    {
        request()->validate([
            'label' => 'required'
        ]);
        Objective::create([
            "role_id" => 3,
            "label" => $request->label,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }


    public function UnderObjectiveStore(Request $request)
    {
        request()->validate([
            'label' => 'required'
        ]);
        under_objective::create([
            "objective_id" => $request->objective_id,
            "label" => $request->label,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }

    public function destroy($id)
    {
        $objective = Objective::find($id);
        $objective->delete();
        return back()->with('message', 'Suppression effectué avec succès!');
    }

    public function destroye($id)
    {
        $under_objective = under_objective::find($id);
        $under_objective->delete();
        return back()->with('message', 'Suppression effectué avec succès!');
    }

    public function objectiveedit(Objective $id)
    {
        $objectives = Objective::paginate(5);
        $services = service::all();
        return view('pages.objective', compact('objective','objectives', 'services'));
    }

    public function objectiveupdate(Request $request, Objective $objective){
        $objective=Objective::find($objective);
        $objective->update([
            "role_id" => 3,
            "label" => $request->label,
        ]);
        return back()->with('message', 'Modification effectué avec succès!');
    }
}
