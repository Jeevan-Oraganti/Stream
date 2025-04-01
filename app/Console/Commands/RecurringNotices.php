<?php

namespace App\Console\Commands;

use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RecurringNotices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notices:schedule-recurring';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate next occurrence of recurring notices and update scheduled_at';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Processing recurring notices (Updating scheduled_at)');

        $now            = Carbon::now()->setTimezone('Asia/Kolkata');
        $todayDayOfWeek = $now->format('l'); // Example: "Monday"

        // Fetch all notices with recurrence settings
        $recurringNotices = Notice::whereNotNull('recurrence')
            ->where(function ($query) use ($now) {
                $query->where('expiry_date', '>=', $now)
                    ->orWhereNull('expiry_date');
            })
            ->orWhere(function ($query) use ($now) {
                $query->where('expiry_date', '<', $now)
                    ->whereNotNull('recurrence');
            })
            ->get();

        Log::info('Found ' . $recurringNotices->count() . ' recurring notices to process.');

        foreach ($recurringNotices as $notice) {
            $nextOccurrence = null;

            switch ($notice->recurrence) {
                case 'daily':
                    $nextOccurrence = $now->addDay()->startOfDay();
                    break;

                case 'weekly':
                    $recurrenceDays = is_string($notice->recurrence_days)
                        ? json_decode($notice->recurrence_days, true)
                        : (is_array($notice->recurrence_days) ? $notice->recurrence_days : []);

                    $nextOccurrence = $this->getNextWeeklyOccurrence($recurrenceDays, $now);

                    break;

                case 'monthly':
                    $dayOfMonth     = Carbon::parse($notice->created_at)->format('d');
                    $nextOccurrence = $this->getNextMonthlyOccurrence($dayOfMonth, $now);
                    break;
            }

            if ($nextOccurrence) {
                $nextOccurrence->setTime(0, 0, 0);
                $expiryAt = $nextOccurrence->copy()->endOfDay();

                $notice->scheduled_at = $nextOccurrence;
                $notice->expiry_date = $expiryAt;
                $notice->save();

                Log::info("Recurring Notice ID {$notice->id} new Scheduled at {$nextOccurrence}, new Expiry: {$expiryAt}");
            }
        }

        Log::info('Recurring notices processed (scheduled_at, expiry_date updated)');
    }

    private function getNextWeeklyOccurrence($recurrenceDays, $now)
    {
        if (empty($recurrenceDays)) return null;

        for ($i = 1; $i <= 7; $i++) {
            $nextDate = $now->copy()->addDays($i);
            if (in_array($nextDate->format('l'), $recurrenceDays)) {
                return $nextDate->startOfDay();
            }
        }

        return null;
    }

    private function getNextMonthlyOccurrence($dayOfMonth, $now)
    {
        $nextMonth = $now->copy()->addMonth();
        return $nextMonth->startOfMonth()->addDays($dayOfMonth - 1);
    }
}
