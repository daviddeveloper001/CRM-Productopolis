<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ProcessBlocksCommand;
use App\Console\Commands\ScheduleBlockTasks;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command(ProcessBlocksCommand::class)->everyMinute()->withoutOverlapping();

//Schedule::command(ScheduleBlockTasks::class)->withoutOverlapping();
