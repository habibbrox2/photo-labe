<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler (cPanel cron: php /path/to/artisan schedule:run)
|--------------------------------------------------------------------------
|
| Shared hosting has no Supervisor, so the database queue is drained by a
| short-lived worker started every minute. Queued notifications and emails
| are processed this way without requiring a permanently running process.
|
*/

Schedule::command('queue:work --stop-when-empty --tries=3 --timeout=90')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();