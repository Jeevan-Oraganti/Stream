<?php

namespace App\Http\Controllers;

use App\Models\Notices;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function getLatestNotice(Request $request)
    {
        $dismissedNotice = $request->cookie('dismissed_notice');

        $notice = Notices::where('expiry_date', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($notice && $dismissedNotice == $notice->id) {
            return response()->json([]);
        }

        return response()->json($notice ? [$notice] : []);
    }
}
