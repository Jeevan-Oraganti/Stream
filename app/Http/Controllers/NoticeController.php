<?php
namespace App\Http\Controllers;

use App\Models\Notices;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function getLatestNotice(Request $request)
    {
        $dismissedNotices = json_decode($request->cookie('dismissed_notice'), true) ?? [];

        $notices = Notices::with('notificationType')
            ->where('expiry_date', '>', now())
            ->whereNotIn('id', $dismissedNotices)
            ->orderBy('created_at')
            ->get();

        return response()->json($notices);
    }
}

