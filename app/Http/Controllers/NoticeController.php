<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\NoticeType;
use App\Models\UserNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class NoticeController extends Controller
{
    //fetch unread notices for guest and logged-in user
    public function unreadNotices(Request $request) {
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
            $dismissedNotices = json_decode($request->input('dismissedNotices', '[]'), true) ?? [];
            Log::info($request->input('dismissedNotices'));
            $query->whereNotIn('id', $dismissedNotices);
        }

        return response()->json($query->get());
    }

    //store dismissed notice for logged-in user
    public function storeUserDismissedNotices($noticeId)
    {
        if (auth()->check()) {
            $userId = auth()->id();

            $userNotice = new UserNotice();
            $userNotice->notice_id = $noticeId;
            $userNotice->user_id = $userId;
            $userNotice->save();

            return response()->json(['message' => 'Notice marked as read.']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    //admin only methods
    public function showNoticesListForAdmin()
    {
        return view('admin.notices.index');
    }

    public function fetchAllNoticesForAdmin(Request $request) {
        $search = $request->input('search');

        $notices = Notice::with('noticeType')
            ->withCount('views')
            ->when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('noticeType', function ($subQuery) use ($search) {
                        $subQuery->whereRaw('LOWER(type) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            })
            ->latest()
            ->get();

        return response()->json(['notices' => $notices]);

    }

    public function createOrUpdateNotice(Request $request)
    {

        $notice = Notice::find($request->id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notice_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'nullable|date',
            'is_sticky' => 'required|boolean',
            'event_date' => 'nullable|date',
            'recurrence' => 'nullable|in:daily,weekly,monthly',
            'recurrence_days' => 'nullable|array',
            'recurrence_days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
        ]);

        $validatedData['recurrence_days'] = $request->has('recurrence_days') ? $request->input('recurrence_days') : [];

        $validatedData['is_sticky'] = $request->boolean('is_sticky') ? '1' : '0';

        if ($validatedData['is_sticky']) {
            if($notice) {
                Notice::where('is_sticky', '1')->where('id' , '!=', $notice->id)->update(['is_sticky' => '0']);
            } else {
                Notice::where('is_sticky', '1')->update(['is_sticky' => '0']);
            }

        }

        if ($notice) {
            $notice->update($validatedData);
        } else {
            $validatedData['created_at'] = now()->setTimezone('Asia/Kolkata');
            $validatedData['updated_at'] = now()->setTimezone('Asia/Kolkata');
            $notice = Notice::create($validatedData);
        }

        return response()->json(['notice' => $notice]);
    }

    public function toggleSticky($noticeId)
    {
        $notice = Notice::find($noticeId);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        //mark all notices a not sticky
        Notice::where('is_sticky', '1')
            ->update(['is_sticky' => '0']);

        if ($notice->is_sticky) {
            $notice->update(['is_sticky' => '0']);
            return response()->json([
                'notice' => $notice,
            ]);
        }

        $notice->update(['is_sticky' => '1']);

        return response()->json([
            'notice' => $notice,
        ]);
    }

    public function noticeDestroy($id)
    {
        $notice = Notice::findOrFail($id);

        if (Gate::allows('delete-notice')) {
            $notice->delete();
        } else {
            return redirect()->route('notices.index')->with('error', 'You do not have permission to delete this notice.');
        }
    }

    public function getNoticeTypes()
    {
        $noticeTypes = NoticeType::all();
        return response()->json(['noticeTypes' => $noticeTypes]);
    }

    public function changeNoticeTypeColorPost(Request $request, $noticeTypeId)
    {
        $request->validate([
            'color' => 'required|string|max:255',
        ]);

        $noticeType = NoticeType::findOrFail($noticeTypeId);
        $noticeType->update(['color' => $request->input('color')]);

        return response()->json(['message' => 'Notice type color updated successfully.']);
    }
}
