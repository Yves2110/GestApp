<?php

namespace App\Http\View\Composers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationComposer
{
    public function __construct(private NotificationService $notifService) {}

    public function compose(View $view): void
    {
        if (Auth::check()) {
            $view->with('_unreadNotifCount', $this->notifService->unreadCount());
        } else {
            $view->with('_unreadNotifCount', 0);
        }
    }
}
