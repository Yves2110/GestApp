<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    public function transition(Request $request, int $id)
    {
        $request->validate([
            'to_status'        => 'required|string|in:draft,pending,validated,rejected',
            'rejection_reason' => 'nullable|string|max:500|required_if:to_status,rejected',
        ]);

        $activity  = Activity::findOrFail($id);
        $toStatus  = $request->to_status;
        $user      = Auth::user();

        if (!$activity->canTransitionTo($toStatus)) {
            return back()->withErrors(['workflow' => "Transition vers « {$toStatus} » non autorisée depuis « {$activity->workflow_status} »."]);
        }

        $roleId = (int) $user->role_id;

        // Règles d'autorisation :
        // draft → pending : rôle Service (4) ou Admin (3) ou SuperAdmin (1)
        // pending → validated|rejected : Admin (3) ou SuperAdmin (1)
        // rejected → draft : tout le monde (remettre en brouillon)
        if ($toStatus === Activity::WF_PENDING && $roleId === 2) {
            abort(403, 'Le Président ne peut pas soumettre une activité.');
        }
        if (in_array($toStatus, [Activity::WF_VALIDATED, Activity::WF_REJECTED]) && !in_array($roleId, [1, 3])) {
            abort(403, 'Seul un Admin ou SuperAdmin peut valider/rejeter une activité.');
        }

        $updates = ['workflow_status' => $toStatus];

        if ($toStatus === Activity::WF_PENDING) {
            $updates['submitted_by'] = $user->id;
            $updates['submitted_at'] = now();
        }

        if ($toStatus === Activity::WF_VALIDATED) {
            $updates['validated_by'] = $user->id;
            $updates['validated_at'] = now();
            $updates['status']       = 1;
        }

        if ($toStatus === Activity::WF_REJECTED) {
            $updates['rejection_reason'] = $request->rejection_reason;
        }

        if ($toStatus === Activity::WF_DRAFT) {
            $updates['rejection_reason'] = null;
        }

        $activity->update($updates);

        $labels = Activity::WORKFLOW_LABELS;
        return back()->with('message', "Activité passée en « {$labels[$toStatus]} » avec succès.");
    }
}
