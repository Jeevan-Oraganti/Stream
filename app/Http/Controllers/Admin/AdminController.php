<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NotificationType;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function noticeIndex()
    {
        $notices = Notice::latest()->get();
        $notificationTypes = NotificationType::all();
        return view('admin.notices.index', compact('notices', 'notificationTypes'));
    }

    public function noticeStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'notification_type_id' => 'required|exists:notification_types,id',
            'expiry_date' => 'nullable|date',
        ]);

        Notice::create($request->except('_token'));

        return redirect()->route('admin.notices.index')->with('success','Notice added successfully.');
    }

    public function noticeUpdate(Request $request, Notice $notice)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'notification_type_id' => 'required|exists:notification_types,id',
            'expiry_date' => 'date',
        ]);

        $notice->update($request->all());

        return redirect()->route('admin.notices.index');
    }

    public function noticeDestroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index');
    }
}
