<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function noticeIndex(Request $request)
    {
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

        return view('admin.notices.index', compact('notices'));
    }


    public function noticeStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notice_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'nullable|date',
            'is_sticky' => 'required|boolean',
            'scheduled_at' => 'nullable|date',
            'created_at' => now()->setTimezone('Asia/Kolkata'),
            'updated_at' => now()->setTimezone('Asia/Kolkata'),
        ]);

        if ($validatedData['is_sticky']) {
            $existingStickyNotice = Notice::where('is_sticky', true)->first();
            if ($existingStickyNotice) {
                return response()->json([
                    'errors' => ['is_sticky' => ['A sticky notice already exists. Please unstick the existing one.']]
                ], 422);
            }
        }

        Notice::create($validatedData);

        return redirect()->route('admin.notices.index')->with('success', 'Notice added successfully.');
    }

    public function noticeUpdate(Request $request, $noticeId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable',
            'notice_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'nullable|date',
            'is_sticky' => 'required|boolean',
            'scheduled_at' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $notice = Notice::find($noticeId);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        if ($request->input('is_sticky')) {
            $existingStickyNotice = Notice::where('is_sticky', '1')->where('id', '!=', $notice->id)->first();
            if ($existingStickyNotice) {
                return response()->json([
                    'errors' => ['is_sticky' => ['A sticky notice already exists. Please unsticky the existing one.']]
                ], 422);
            }
        }

        $notice->update($request->all());

        return response()->json([
            'notice' => $notice,
        ]);
    }

    public function toggleSticky($noticeId)
    {
        $notice = Notice::find($noticeId);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        Notice::where('is_sticky', true)->update(['is_sticky' => false]);

        if ($notice->is_sticky) {
            $notice->update(['is_sticky' => false]);
            return response()->json([
                'notice' => $notice,
            ]);
        }

        $notice->update(['is_sticky' => true]);

        return response()->json([
            'notice' => $notice,
        ]);
    }

    public function noticeDestroy($id)
    {
        //        sleep(2);
        $notice = Notice::findOrFail($id);

        if (Gate::allows('delete-notice')) {
            $notice->delete();
            return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
        } else {
            return redirect()->route('admin.notices.index')->with('error', 'You do not have permission to delete this notice.');
        }
    }

    public function NoticeTypeColor()
    {
        return view('admin.notices.change-color');
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
