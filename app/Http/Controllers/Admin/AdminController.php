<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function show()
    {
        $notifications = cache()->get('admin_notifications', []);
        return view('admin.notices.index', compact('notifications'));

        // return view('admin.notices.index');
    }

    //fetch all notices
    public function notices(Request $request)
    {
//        $notifications = Auth::user()->notifications()->latest()->get();

        $search = $request->input('search');

        $notices = Notice::with('noticeType')
            ->when($search, function ($query, $search) {
                return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('noticeType', function ($subQuery) use ($search) {
                        $subQuery->whereRaw('LOWER(type) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            })->latest()->get();


        if ($request->ajax()) {
            return response()->json(['notices' => $notices]);
        }

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
            'scheduled_at' => 'nullable|date',
            'recurrence' => 'nullable|in:daily,weekly,monthly',
            'recurrence_days' => 'nullable|array',
            'recurrence_days.*' => 'string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday'
        ]);

        $validatedData['recurrence_days'] = $request->has('recurrence_days') ? $request->input('recurrence_days') : [];


        $validatedData['is_sticky'] = $request->boolean('is_sticky') ? '1' : '0';

        if ($request->input('is_sticky')) {
            $existingStickyNotice = Notice::where('is_sticky', '1')->where('id', '!=', $notice->id)->first();
            if ($existingStickyNotice) {
                $existingStickyNotice->update(['is_sticky' => '0']);
            }
        }

        if ($notice) {
            $notice->update($validatedData);
        } else {
            $validatedData['created_at'] = now()->setTimezone('Asia/Kolkata');
            $validatedData['updated_at'] = now()->setTimezone('Asia/Kolkata');
            $notice                      = Notice::create($validatedData);
        }

        return response()->json(['notice' => $notice]);
    }

    public function toggleSticky($noticeId)
    {
        $notice = Notice::find($noticeId);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

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
            return redirect()->route('notices.index')->with('success', 'Notice deleted successfully.');
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
