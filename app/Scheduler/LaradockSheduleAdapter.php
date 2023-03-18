<?php

namespace App\Scheduler;

use Closure;
use Illuminate\Console\Scheduling\Schedule;

class LaradockSheduleAdapter implements ISchedule
{
    protected Schedule $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function exec(string $command, array $parameters = []): IEvent
    {
        $event = $this->schedule->exec($command, $parameters);

        return new LaradockEventAdapter($event);
    }

    public function call(Closure $callback, array $parameters = []): IEvent
    {
        $event = $this->schedule->call($callback, $parameters);

        return new LaradockEventAdapter($event);
    }

}
