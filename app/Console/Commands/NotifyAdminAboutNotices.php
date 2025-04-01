<?php

namespace App\Console\Commands;

use App\Models\Notice;
use App\Models\User;
use App\Notifications\NoticeAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class NotifyAdminAboutNotices extends Command
{
    protected $signature = 'notices:notify-admin-about-notices';
    protected $description = 'Notify admins about notices that are about to go live or expire';

    public function handle()
    {
        $now = now();
        $next24Hours = (clone $now)->addHours(24);

        $upcomingNotices = Notice::where('scheduled_at', '>', $now)
            ->where('scheduled_at', '<=', $next24Hours)
            ->get();

        $expiringNotices = Notice::where('expiry_date', '>', $now)
            ->where('expiry_date', '<=', $next24Hours)
            ->get();

        $notifications = [];

        foreach ($upcomingNotices as $notice) {
            $notifications[] = [
            'message' => "Notice '{$notice->name}' is scheduled to go live on " . $notice->scheduled_at->format('F j, Y, g:i a') . ".",
            'time' => now()->format('F j, Y, g:i a'),
            ];
        }

        foreach ($expiringNotices as $notice) {
            $notifications[] = [
            'message' => "Notice '{$notice->name}' is expiring on " . $notice->expiry_date->format('F j, Y, g:i a') . ".",
            'time' => now()->format('F j, Y, g:i a'),
            ];
        }

        // Store notifications in database cache
        Cache::put('admin_notifications', $notifications, now()->addMinutes(10));

        $this->info('Admin notifications stored in database cache.');
    }
}
