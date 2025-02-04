<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notices;
use App\Models\NotificationType;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function noticeIndex()
    {
        $notices = Notices::latest()->get();
        $notificationTypes = NotificationType::all();
        return view('admin.notices.index', compact('notices', 'notificationTypes'));
    }

    public function noticeStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'notification_type_id' => 'required|exists:notification_types,id',
            'expiry_date' => 'required|date',
        ]);

        Notices::create($request->except('_token'));

        return redirect()->route('admin.notices.index')->with('success','Notice added successfully.');
    }

    public function noticeUpdate(Request $request, Notices $notice)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'notification_type_id' => 'required|exists:notification_types,id',
            'expiry_date' => 'required|date',
        ]);

        $notice->update($request->all());

        return redirect()->route('admin.notices.index');
    }

    public function noticeDestroy(Notices $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index');
    }
}
