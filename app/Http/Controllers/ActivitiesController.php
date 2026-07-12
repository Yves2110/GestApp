<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\Objective;
use App\Models\Periode;
use App\Models\Service;
use App\Models\UnderObjective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ActivitiesController extends Controller
{
    /**
     * Rôle "Service" : ne gère que son propre service.
     */
    private const SERVICE_ROLE_ID = 4;

    /**
     * Détermine si l'utilisateur connecté est un utilisateur de type Service.
     */
    private function isServiceUser(): bool
    {
        return Auth::check() && (int) Auth::user()->role_id === self::SERVICE_ROLE_ID;
    }

    /**
     * Récupère une activité en s'assurant qu'un utilisateur Service
     * ne peut accéder qu'aux activités de son propre service.
     */
    private function findAuthorizedActivity($id): Activity
    {
        $activity = Activity::findOrFail($id);

        if ($this->isServiceUser() && (int) $activity->service_id !== (int) Auth::user()->service_id) {
            abort(403, "Vous ne pouvez gérer que les activités de votre service.");
        }

        return $activity;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $objectives      = Cache::remember('objectives_tree', 300, fn() => Objective::with(['underObjectives'])->get());
        $underobjectives = Cache::remember('under_objectives_all', 300, fn() => UnderObjective::with('objective')->get());
        $trimestres      = Cache::remember('periodes_all', 300, fn() => Periode::all());

        $query = Activity::with(['service', 'objective', 'underObjective', 'periode']);

        if ($this->isServiceUser()) {
            $query->where('service_id', $user->service_id);
            $services = Service::where('id', $user->service_id)->get();
        } else {
            $services = Service::all();
        }

        // Filtres GET
        if ($search = $request->input('search')) {
            $query->where('label', 'like', "%{$search}%");
        }
        if ($serviceId = $request->input('service_id')) {
            $query->where('service_id', $serviceId);
        }
        if ($workflowStatus = $request->input('workflow_status')) {
            $query->where('workflow_status', $workflowStatus);
        }
        if ($periodeId = $request->input('periode_id')) {
            $query->where('periode_id', $periodeId);
        }

        $activities = $query->latest()->paginate(15)->withQueryString();

        $filters = $request->only(['search', 'service_id', 'workflow_status', 'periode_id']);

        return view('pages.activites-modern', compact(
            'services', 'objectives', 'underobjectives', 'activities', 'trimestres', 'filters'
        ));
    }

    public function ActivitiesStore(StoreActivityRequest $request)
    {
        $user = Auth::user();

        // Un utilisateur Service ne peut créer que pour SON propre service :
        // on force le service_id quelle que soit la valeur soumise.
        $serviceId = $this->isServiceUser()
            ? $user->service_id
            : $request->service_id;

        Activity::create([
            'service_id'        => $serviceId,
            'objective_id'      => $request->objective_id,
            'under_objective_id'=> $request->under_objective_id,
            'periode_id'        => $request->periode_id,
            'label'             => $request->label,
            'indicator'         => $request->indicator,
            'target'            => $request->target,
            'price'             => $request->price,
            'source_of_funding' => $request->source_of_funding,
            'structure'         => $request->structure,
            'status'            => 0,
            'commentary'        => $request->commentary,
        ]);

        return back()->with('message', 'Enregistrement effectué avec succès!');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $activity = $this->findAuthorizedActivity($id);

        $objectives      = Cache::remember('objectives_tree', 300, fn() => Objective::with(['underObjectives'])->get());
        $underobjectives = Cache::remember('under_objectives_all', 300, fn() => UnderObjective::with('objective')->get());
        $trimestres      = Cache::remember('periodes_all', 300, fn() => Periode::all());

        $services = $this->isServiceUser()
            ? Service::where('id', $user->service_id)->get()
            : Service::all();

        return view('pages.editactivite', compact('activity', 'services', 'objectives', 'underobjectives', 'trimestres'));
    }

    public function update(UpdateActivityRequest $request, $id)
    {
        $user = Auth::user();
        $activity = $this->findAuthorizedActivity($id);

        // Un utilisateur Service ne peut pas réaffecter l'activité à un autre service.
        $serviceId = $this->isServiceUser()
            ? $user->service_id
            : $request->service_id;

        $activity->update([
            'service_id'        => $serviceId,
            'objective_id'      => $request->objective_id,
            'under_objective_id'=> $request->under_objective_id,
            'periode_id'        => $request->periode_id,
            'label'             => $request->label,
            'indicator'         => $request->indicator,
            'target'            => $request->target,
            'price'             => $request->price,
            'source_of_funding' => $request->source_of_funding,
            'structure'         => $request->structure,
            'commentary'        => $request->commentary,
        ]);

        return redirect()->route('Activites')->with('message', 'Modification effectuée avec succès!');
    }

    public function kanban()
    {
        $user  = Auth::user();
        $query = Activity::with(['service', 'objective', 'periode']);

        if ($this->isServiceUser()) {
            $query->where('service_id', $user->service_id);
        }

        $activities = $query->latest()->get();

        return view('pages.activites-kanban', compact('activities'));
    }

    public function show($id)
    {
        $activity = $this->findAuthorizedActivity($id);
        $activity->load(['service', 'objective', 'underObjective', 'periode', 'statusHistory.user', 'submittedBy', 'validatedBy']);
        return view('pages.activite-show', compact('activity'));
    }

    public function destroy($id)
    {
        $activity = $this->findAuthorizedActivity($id);
        $activity->delete();

        return back()->with('message', 'Suppression effectuée avec succès!');
    }
}
