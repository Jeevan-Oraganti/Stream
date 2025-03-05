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
        sleep(2);
        $search = $request->input('search');

        $notices = Notice::with('noticeType')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('noticeType', function ($subQuery) use ($search) {
                        $subQuery->where('type', 'like', "%{$search}%");
                    });
            })
            ->latest()->paginate(5);

        if ($request->ajax()) {
            return response()->json([
                'notices' => $notices->items(),
                'pagination' => [
                    'current_page' => $notices->currentPage(),
                    'last_page' => $notices->lastPage(),
                    'per_page' => $notices->perPage(),
                    'total' => $notices->total(),
                    'next_page_url' => $notices->nextPageUrl(),
                    'prev_page_url' => $notices->previousPageUrl(),
                ],
            ]);
        }

        return view('admin.notices.index', compact('notices'));
    }


    public function noticeStore(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'notice_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'nullable|date',
            'is_sticky' => 'required|boolean',
            'created_at' => now(),
            'updated_at' => now(),
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

    public function addNotice()
    {
        return view('admin.notices.add-notice');
    }

    public function editNotice($id)
    {
        $notice = Notice::with('noticeType')->findOrFail($id);
        return view('admin.notices.edit-notice', ['notice' => $notice]);
    }

    public function noticeUpdate(Request $request, $noticeId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'notice_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'nullable|date',
            'is_sticky' => 'required|boolean',
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
        sleep(5);
        $notice = Notice::findOrFail($id);

        if (Gate::allows('delete-notice')) {
            $notice->delete();
            return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
        } else {
            return redirect()->route('admin.notices.index')->with('error', 'You do not have permission to delete this notice.');
        }
    }
}
