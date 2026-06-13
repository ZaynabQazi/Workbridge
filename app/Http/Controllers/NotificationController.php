<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $items = $request->user()->appNotifications()->latest()->take(10)->get();
        return response()->json([
            'unread_count' => $items->whereNull('read_at')->count(),
            'notifications' => $items->map(fn ($n) => [
                'id'      => $n->id,
                'title'   => $n->title,
                'message' => $n->message,
                'read_at' => $n->read_at,
            ]),
        ]);
    }

    public function read(AppNotification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);
        $notification->update(['read_at' => now()]);
        return response()->json(['ok' => true]);
    }
}
