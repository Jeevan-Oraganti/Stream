<?php

namespace App\Http\Controllers;

use App\Models\Notices;
use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Notices;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function getLatestNotice(Request $request)
    {
        $dismissedNotices = json_decode($request->cookie('dismissed_notice'), true) ?? [];

        $notice = Notices::with('notificationType')
            ->where('expiry_date', '>', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($notice && in_array($notice->id, $dismissedNotices)) {
            return response()->json([]);
        }

        if ($notice && $dismissedNotices && !in_array($notice->id, $dismissedNotices)) {
            $notice = Notices::where('expiry_date', '>', now())
                ->whereNotIn('id', $dismissedNotices)
                ->orderBy('created_at', 'desc')
                ->first();
        }

        return response()->json($notice ? [$notice] : []);
    }
}
