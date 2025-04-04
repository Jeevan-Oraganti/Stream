<?php

use App\Models\Notice;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use app\Console\Commands\ScheduledNotices;
use app\Console\Commands\RecurringNotices;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('notices:scheduled-notices')->everyMinute();
//Schedule::command('notices:schedule-recurring')->everyMinute();
//Schedule::command('notices:notify-admin-about-notices')->everyMinute();
