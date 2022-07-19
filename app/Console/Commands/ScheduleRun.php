<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;

class ScheduleRun extends ScheduleRunCommand
{
    protected $signature = 'schedule:run {--sleep=0 : The number of seconds to sleep before running the schedule}';


    public function handle(Schedule $schedule, Dispatcher $dispatcher, ExceptionHandler $handler)
    {
        sleep($this->option('sleep'));

        parent::handle($schedule, $dispatcher, $handler);
    }
}
