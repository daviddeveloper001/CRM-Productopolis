<?php

use App\Jobs\ProcessBlocksJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ProcessBlocksCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//Schedule::command(ProcessBlocksCommand::class)->everyMinute()->withoutOverlapping();


Schedule::job(new ProcessBlocksJob())->everyMinute()->withoutOverlapping();

