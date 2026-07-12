<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\ActivityStatusHistory;
use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function updating(Activity $activity): void
    {
        if ($activity->isDirty('workflow_status')) {
            $old = $activity->getOriginal('workflow_status');
            $new = $activity->workflow_status;
            $userId = Auth::id() ?? $activity->submitted_by ?? 1;

            ActivityStatusHistory::create([
                'activity_id' => $activity->id,
                'user_id'     => $userId,
                'from_status' => $old,
                'to_status'   => $new,
                'comment'     => $activity->rejection_reason ?? null,
            ]);

            $this->dispatchNotifications($activity, $old, $new);
        }
    }

    private function dispatchNotifications(Activity $activity, string $from, string $to): void
    {
        $label = \Illuminate\Support\Str::limit($activity->label, 50);

        match ($to) {
            Activity::WF_PENDING => $this->notifyAdmins(
                $activity,
                'activity_submitted',
                'Nouvelle activité soumise',
                "L'activité « {$label} » est en attente de validation."
            ),
            Activity::WF_VALIDATED => $this->notifyUser(
                $activity,
                $activity->submitted_by,
                'activity_validated',
                'Activité validée',
                "Votre activité « {$label} » a été validée."
            ),
            Activity::WF_REJECTED => $this->notifyUser(
                $activity,
                $activity->submitted_by,
                'activity_rejected',
                'Activité rejetée',
                "Votre activité « {$label} » a été rejetée." .
                ($activity->rejection_reason ? " Motif : {$activity->rejection_reason}" : '')
            ),
            Activity::WF_DRAFT => $this->notifyUser(
                $activity,
                $activity->submitted_by,
                'activity_reopen',
                'Activité remise en brouillon',
                "L'activité « {$label} » a été remise en brouillon."
            ),
            default => null,
        };
    }

    private function notifyAdmins(Activity $activity, string $type, string $title, string $message): void
    {
        $url = route('activites.show', $activity->id);
        $admins = User::whereIn('role_id', [1, 3])->get();

        foreach ($admins as $admin) {
            AppNotification::create([
                'user_id'      => $admin->id,
                'type'         => $type,
                'title'        => $title,
                'message'      => $message,
                'related_id'   => $activity->id,
                'related_type' => Activity::class,
                'action_url'   => $url,
            ]);
        }
    }

    private function notifyUser(Activity $activity, ?int $userId, string $type, string $title, string $message): void
    {
        if (!$userId) return;

        AppNotification::create([
            'user_id'      => $userId,
            'type'         => $type,
            'title'        => $title,
            'message'      => $message,
            'related_id'   => $activity->id,
            'related_type' => Activity::class,
            'action_url'   => route('activites.show', $activity->id),
        ]);
    }
}
