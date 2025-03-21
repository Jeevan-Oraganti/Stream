<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    /**
     * Fetch unread notices for guest users.
     * Notices are filtered based on their active status, schedule, expiry date, and dismissed notices stored in cookies.
     */
    public function unreadNoticesForGuest(Request $request)
    {
        $dismissedNotices = json_decode($request->cookie('dismissed_notice', '[]'), true) ?? [];

        $notices = Notice::with('noticeType')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('scheduled_at', '<=', now())
                    ->orWhereNull('scheduled_at');
            })
            ->where(function ($query) {
                $query->where('expiry_date', '>', now())
                    ->orWhereNull('expiry_date');
            })
            ->whereNotIn('id', $dismissedNotices)
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($notices);
    }

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
     * Fetch unread notices specifically for authenticated users.
     * Notices are filtered based on their active status, schedule, expiry date, and the absence of an entry in the `user_notices` table.
     */
    public function unreadNoticesForUser()
    {
        if (Auth::check()) {
            $userId = Auth::id();

            $unreadNotices = Notice::with('noticeType')
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->where('scheduled_at', '<=', now())
                        ->orWhereNull('scheduled_at');
                })
                ->where(function ($query) {
                    $query->where('expiry_date', '>', now())
                        ->orWhereNull('expiry_date');
                })
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
