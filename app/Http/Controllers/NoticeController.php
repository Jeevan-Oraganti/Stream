<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    /**
     * Fetch unread notices for both authenticated and guest users.
     * Authenticated users' unread notices are determined by the absence of an entry in the `user_notices` table.
     * Guest users' unread notices are filtered based on dismissed notices stored in cookies.
     */
    public function unreadNotices(Request $request)
    {
        $query = Notice::with('noticeType')
            ->whereRaw('"is_active"::BOOLEAN = TRUE')
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc');

        if (Auth::check()) {
            $userId = Auth::id();

            $query->leftJoin('user_notices', function ($join) use ($userId) {
                $join->on('notices.id', '=', 'user_notices.notice_id')
                    ->where('user_notices.user_id', '=', $userId);
            })->whereNull('user_notices.user_id')
                ->select('notices.*');
        } else {
            $dismissedNotices = json_decode($request->cookie('dismissed_notice', '[]'), true) ?? [];
            $query->whereNotIn('id', $dismissedNotices);
        }

        return response()->json($query->get());
    }

    /**
     * Mark a specific notice as read for the authenticated user.
     * This is done by inserting an entry into the `user_notices` table.
     */
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
