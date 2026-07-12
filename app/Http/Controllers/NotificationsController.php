<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function __construct(private NotificationService $notifService) {}

    public function index()
    {
        $notifications = $this->notifService->getForUser(limit: 30);
        $unread = $this->notifService->unreadCount();
        return view('pages.notifications', compact('notifications', 'unread'));
    }

    public function markRead(int $id)
    {
        $this->notifService->markRead($id);
        return response()->json(['ok' => true]);
    }

    public function markAllRead()
    {
        $this->notifService->markAllRead();
        return back()->with('message', 'Toutes les notifications ont été lues.');
    }

    public function unreadCount()
    {
        return response()->json(['count' => $this->notifService->unreadCount()]);
    }
}
