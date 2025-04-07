<?php

namespace App\Console\Commands;

use App\Models\Notice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        DB::beginTransaction();
        try {
            // Deactivate all active notices
            DB::table('notices')->where('is_active', true)->update(['is_active' => false]);
            Log::info('All active notices have been deactivated.');

            // Fetch notices to activate
            $noticesToActivate = Notice::fromQuery("
            SELECT * FROM
                         (SELECT n.*,
                                 (SELECT COUNT(mc.calendar_date)
                                  FROM market_calendars mc
                                  WHERE mc.calendar_date :: DATE BETWEEN NOW() :: DATE
                                      AND n.event_date :: DATE
                                    AND mc.is_holiday IS FALSE
                                    AND mc.calendar_date :: DATE != NOW() :: DATE) AS days_before_active
                        FROM notices n
                        WHERE n.is_active = false
                          AND (n.expiry_date > NOW() OR n.expiry_date IS NULL)
                        ORDER BY n.event_date) AS SUB_QUERY
                     WHERE (sub_query.notice_type_id = 4 AND SUB_QUERY.days_before_active = 1)
                        OR (sub_query.notice_type_id != 4)
        ");


            // Activate selected notices
            foreach ($noticesToActivate as $notice) {
                $threshold = 1;
                if ($notice->days_before_active <= $threshold) {
                    $notice->is_active = true;
                    $notice->update();
                    //       DB::table('notices')->where('id', $notice->id)->update(['is_active' => true]);
                    Log::info("Notice ID {$notice->id} is now active.");
                }
            }

            DB::commit();
            Log::info('Scheduled notices processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error processing scheduled notices: ' . $e->getMessage());
        }
    }
}
