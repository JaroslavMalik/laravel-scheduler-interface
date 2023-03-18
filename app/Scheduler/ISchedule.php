<?php

namespace App\Scheduler;

use Closure;

interface ISchedule
{
    /**
     * Add a new command event to the schedule.
     *
     * @param string $command
     * @param string[]        $parameters
     *
     * @return IEvent
     */
    public function exec(string $command, array $parameters = []): IEvent;

    /**
     * Add a new callback event to the schedule.
     *
     * @param  Closure  $callback
     * @param  array  $parameters
     * @return IEvent
     */
    public function call(Closure $callback, array $parameters = []): IEvent;
}
