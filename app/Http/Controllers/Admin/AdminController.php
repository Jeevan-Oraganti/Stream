<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notices;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function noticeIndex()
    {
        $notices = Notices::latest()->get();
        return view('admin.notices.index', compact('notices'));
    }

    public function noticeStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'notification_type_id' => 'required|in:announcement,information,outage',
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
            'notification_type_id' => 'required|in:announcement,information,outage',
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
