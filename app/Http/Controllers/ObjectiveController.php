<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreObjectiveRequest;
use App\Http\Requests\StoreUnderObjectiveRequest;
use App\Http\Requests\UpdateObjectiveRequest;
use App\Models\Objective;
use App\Models\Service;
use App\Models\UnderObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ObjectiveController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $objectives = Objective::with(['underObjectives'])->paginate(5);
        return view('pages.objective', compact('services', 'objectives'));
    }

    public function under_index()
    {
        $services = Service::all();
        $objectives = Objective::with('underObjectives')->get();
        $under_objectives = UnderObjective::with('objective')->get();
        return view('pages.under_objective', compact('services', 'objectives', 'under_objectives'));
    }

    public function ObjectiveStore(StoreObjectiveRequest $request)
    {
        Objective::create([
            'role_id' => 3,
            'label'   => $request->label,
        ]);
        Cache::forget('objectives_tree');

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }

    public function UnderObjectiveStore(StoreUnderObjectiveRequest $request)
    {
        UnderObjective::create([
            'objective_id' => $request->objective_id,
            'label'        => $request->label,
        ]);
        Cache::forget('objectives_tree');
        Cache::forget('under_objectives_all');

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }

    public function destroy($id)
    {
        $objective = Objective::findOrFail($id);
        $objective->delete();
        Cache::forget('objectives_tree');
        Cache::forget('under_objectives_all');
        return back()->with('message', 'Suppression effectuée avec succès!');
    }

    public function destroye($id)
    {
        $underObjective = UnderObjective::findOrFail($id);
        $underObjective->delete();
        Cache::forget('objectives_tree');
        Cache::forget('under_objectives_all');
        return back()->with('message', 'Suppression effectuée avec succès!');
    }

    public function objectiveedit($id)
    {
        $objective = Objective::findOrFail($id);
        $services = Service::all();
        return view('pages.editobjective', compact('objective', 'services'));
    }

    public function objectiveupdate(UpdateObjectiveRequest $request, $id)
    {
        $objective = Objective::findOrFail($id);
        $objective->update([
            'role_id' => 3,
            'label'   => $request->label,
        ]);
        Cache::forget('objectives_tree');
        Cache::forget('under_objectives_all');
        return redirect()->route('Objective')
            ->with('message', 'Modification effectuée avec succès!');
    }
}
