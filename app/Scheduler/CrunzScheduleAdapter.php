<?php

namespace App\Scheduler;

use Closure;
use Crunz\Schedule;

class CrunzScheduleAdapter implements ISchedule
{
    protected Schedule $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function exec(string $command, array $parameters = []): IEvent
    {
        $event = $this->schedule->run($command, $parameters);

        return new CrunzEventAdapter($event);
    }

    public function call(Closure $callback, array $parameters = []): IEvent
    {
        $event = $this->schedule->run($callback, $parameters);

        return new CrunzEventAdapter($event);
    }

}
