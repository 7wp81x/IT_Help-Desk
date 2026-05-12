<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\UserAnnouncementRead;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Announcement::active();

        // Filter by read status
        if ($request->filled('read_status')) {
            $userId = auth()->id();
            if ($request->read_status === 'read') {
                $query->whereHas('reads', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            } elseif ($request->read_status === 'unread') {
                $query->whereDoesntHave('reads', function($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
            }
        }

        $announcements = $query->orderBy('published_at', 'desc')->paginate(10);

        // Mark announcements as read for current user
        $unreadIds = $announcements->filter(function($announcement) {
            return !$announcement->isReadBy(auth()->user());
        })->pluck('id');

        if ($unreadIds->isNotEmpty()) {
            $readData = $unreadIds->map(function($id) {
                return [
                    'user_id' => auth()->id(),
                    'announcement_id' => $id,
                    'read_at' => now(),
                ];
            });
            UserAnnouncementRead::insert($readData->toArray());
        }

        return view('user.announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        if (!$announcement->is_active || $announcement->published_at > now()) {
            abort(404);
        }

        // Mark as read
        UserAnnouncementRead::firstOrCreate([
            'user_id' => auth()->id(),
            'announcement_id' => $announcement->id,
        ], [
            'read_at' => now(),
        ]);

        return view('user.announcements.show', compact('announcement'));
    }

    public function markAsRead(Announcement $announcement)
    {
        UserAnnouncementRead::firstOrCreate([
            'user_id' => auth()->id(),
            'announcement_id' => $announcement->id,
        ], [
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
