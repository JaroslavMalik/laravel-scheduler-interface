<?php

namespace App\Scheduler;

use Closure;
use DateTimeZone;
use Illuminate\Console\Scheduling\Event;

interface IEvent
{
    /**
     * The Cron expression representing the event's frequency.
     *
     * @param string $expression
     * @return IEvent
     */
    public function cron(string $expression): IEvent;

    /**
     * Set the timezone the date should be evaluated on.
     *
     * @param DateTimeZone|string $timezone
     * @return IEvent
     */
    public function timezone(DateTimeZone|string $timezone): IEvent;

    /**
     * Register a callback to further filter the schedule.
     *
     * @param Closure $callback
     * @return IEvent
     */
    public function when(Closure $callback): IEvent;

    /**
     * Register a callback to further filter the schedule.
     *
     * @param Closure $callback
     * @return IEvent
     */
    public function skip(Closure $callback): IEvent;

    /**
     * Do not allow the event to overlap each other.
     *
     * Set the event mutex implementation to be used.
     * The expiration time of the underlying cache lock may be specified in minutes.
     *
     * @param IMutex|null $mutex
     * @param int $ttl
     * @return IEvent
     */
    public function preventOverlapping(?IMutex $mutex, int $ttl = 1440): IEvent;

    /**
     * Get the mutex name for the scheduled command.
     *
     * @return string
     */
    public function getMutexName(): string;

    /**
     * Get the mutex event for the scheduled command.
     *
     * @return Event
     */
    public function getMutexEvent(): Event;

    /**
     * Get the number of minutes the mutex should be valid.
     *
     * @return Event
     */
    public function getMutexTTL(): int;

    /**
     * Send the output of the command to a given location.
     *
     * @param string $location
     * @param bool $append
     * @return IEvent
     */
    public function sendOutputTo(string $location, bool $append = false): IEvent;

    /**
     * Register a callback to be called before the operation.
     *
     * @param Closure $callback
     * @return $this
     */
    public function before(Closure $callback): IEvent;

    /**
     * Register a callback to be called after the operation.
     *
     * @param Closure $callback
     * @return $this
     */
    public function after(Closure $callback): IEvent;

    /**
     * Register a callback to ping a given URL before the job runs.
     *
     * @param string $url
     * @return $this
     */
    public function pingBefore($url): IEvent;

    /**
     * Register a callback to ping a given URL after the job runs.
     *
     * @param string $url
     * @return $this
     */
    public function pingAfter($url): IEvent;
}
