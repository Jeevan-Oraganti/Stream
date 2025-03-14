<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function unreadNoticesForGuest(Request $request)
    {
        $dismissedNotices = json_decode($request->cookie('dismissed_notice', '[]'), true) ?? [];

        $notices = Notice::with('noticeType')
            ->where('is_active', true)
            ->whereNotIn('id', $dismissedNotices)
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc')

            ->get();

        return response()->json($notices);
    }


    public function unreadNoticesForUser()
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $unreadNotices = Notice::with('noticeType')
                ->where('is_active', true)
                ->leftJoin('user_notices', function ($join) use ($userId) {
                    $join->on('notices.id', '=', 'user_notices.notice_id')
                        ->where('user_notices.user_id', '=', $userId);
                })
                ->whereNull('user_notices.user_id')
                ->select('notices.*')
                ->orderBy('is_sticky', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($unreadNotices);
        }
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function acknowledge($noticeId)
    {
        if (auth()->check()) {
            $userId = auth()->id();

            DB::table('user_notices')->insert([
                'notice_id' => $noticeId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Notice marked as read.']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
