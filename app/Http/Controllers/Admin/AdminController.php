<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeType;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function noticeIndex(Request $request)
    {
        $notices = Notice::with('noticeType')->latest()->paginate(5);

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
            'notification_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'nullable|date',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $notice = Notice::create($validatedData);

        return response()->json([
            'message' => 'Notice added successfully.',
            'notice' => $notice
        ], 201);
    }

    public function noticeUpdate(Request $request, Notice $notice)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'notification_type_id' => 'required|exists:notice_types,id',
            'expiry_date' => 'date',
        ]);

        $notice->update($request->all());

        return redirect()->route('admin.notices.index');
    }

    public function noticeDestroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return response()->json([
            'message' => 'Notice deleted successfully.'
        ], 200);
    }
}
