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
        sleep(5);
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

        $notice = Notice::create($validatedData);

        return response()->json([
            'message' => 'Notice added successfully.',
            'notice' => $notice
        ], 201);
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

        $notice->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'notice_type_id' => $request->input('notice_type_id'),
            'expiry_date' => $request->input('expiry_date'),
            'is_sticky' => $request->input('is_sticky'),
        ]);

        return response()->json([
            'message' => 'Notice updated successfully',
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
