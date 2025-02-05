<?php

namespace App\Http\Controllers;

use App\Models\Notices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function getLatestNotice(Request $request)
    {
        $dismissedNotices = json_decode($request->cookie('dismissed_notice', '[]'), true) ?? [];

        $notices = Notices::with('notificationType')
            ->where(function ($query) {
                $query->where('expiry_date', '>', now())
                    ->orWhereNull('expiry_date');
            })
            ->whereNotIn('id', $dismissedNotices)
            ->orderBy('created_at')
            ->get();

        return response()->json($notices);
    }


    public function unread()
    {
        if (auth()->check()) {
            $userId = auth()->id();

            $unreadNotices = DB::table('notices')
                ->leftJoin('user_notices', 'notices.id', '=', 'user_notices.notice_id')
                ->whereNull('user_notices.user_id')
                ->orWhere('user_notices.user_id', '!=', $userId)
                ->select('notices.*')
                ->orderBy('created_at')
                ->get();

            return response()->json($unreadNotices);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function markAsRead($noticeId)
    {
        if (auth()->check()) {
            $userId = auth()->id();

            $exists = DB::table('user_notices')
                ->where('notice_id', $noticeId)
                ->where('user_id', $userId)
                ->exists();

            if (!$exists) {
                DB::table('user_notices')->insert([
                    'notice_id' => $noticeId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return response()->json(['message' => 'Notice marked as read.']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
