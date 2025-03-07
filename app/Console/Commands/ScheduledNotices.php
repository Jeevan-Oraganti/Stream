<?php

namespace App\Console\Commands;

use App\Models\Notice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduledNotices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notices:scheduled-notices';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled notices to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Sending scheduled notices');

        $noticesToActivate = Notice::where(function ($query) {
            $query->where('scheduled_at', '<=', now())
                ->orWhereNull('scheduled_at')
                ->where('expiry_date', '>', now())
                ->orWhereNull('expiry_date');
            })
            ->where('is_active', false)
            ->get();

        foreach ($noticesToActivate as $notice) {
            $notice->update(['is_active' => true]);
            Log::info("Notice ID {$notice->id} is now active.");
        }

        $noticesToDeactivate = Notice::where(function ($query) {
            $query->where('expiry_date', '<=', now())
                ->whereNotNull('expiry_date')
                ->orWhere('scheduled_at', '>', now())
                ->whereNotNull('scheduled_at');
        })
            ->where('is_active', true)
            ->get();

        foreach ($noticesToDeactivate as $notice) {
            $notice->update(['is_active' => false]);
            Log::info("Notice ID {$notice->id} has expired or is scheduled for the future and is now inactive.");
        }

        Log::info('Scheduled notices Sent');
    }
}
