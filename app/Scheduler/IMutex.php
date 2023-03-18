<?php

namespace App\Scheduler;

use Closure;

interface IMutex
{
    /**
     * Attempt to obtain an event mutex for the given event.
     *
     * @param  IEvent   $event
     * @return bool
     */
    public function create(IEvent $event): bool;

    /**
     * Determine if an event mutex exists for the given event.
     *
     * @param  IEvent   $event
     * @return bool
     */
    public function exists(IEvent $event): bool;

    /**
     * Clear the event mutex for the given event.
     *
     * @param  IEvent   $event
     * @return void
     */
    public function delete(IEvent $event): void;
}
