<?php

namespace App\Scheduler;

use Closure;
use DateTimeZone;
use Illuminate\Console\Scheduling\Event;

class LaradockEventAdapter implements IEvent
{
    protected Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function cron(string $expression): IEvent
    {
        $this->event->cron($expression);

        return $this;
    }

    public function timezone(DateTimeZone|string $timezone): IEvent
    {
        $this->event->timezone($timezone);

        return $this;
    }

    public function when(Closure $callback): IEvent
    {
        $this->event->when($callback);

        return $this;
    }

    public function skip(Closure $callback): IEvent
    {
        $this->event->skip($callback);

        return $this;
    }

    public function preventOverlapping(?IMutex $mutex, int $ttl = 1440): IEvent
    {
        if ($mutex !== null) {
            $eventMutex = new EventMutexAdapter($mutex, $this);
            $this->event->preventOverlapsUsing($eventMutex);
        }

        $this->event->withoutOverlapping($ttl);

        return $this;
    }

    public function getMutexName(): string
    {
        return $this->event->mutexName();
    }

    public function getMutexEvent(): Event
    {
        return $this->event;
    }

    public function getMutexTTL(): int
    {
        return $this->event->expiresAt;
    }

    public function sendOutputTo(string $location, bool $append = false): IEvent
    {
        $this->event->sendOutputTo($location, $append);

        return $this;
    }

    public function before(Closure $callback): IEvent
    {
        $this->event->before($callback);

        return $this;
    }

    public function after(Closure $callback): IEvent
    {
        $this->event->after($callback);

        return $this;
    }

    public function pingBefore($url): IEvent
    {
        $this->event->pingBefore($url);

        return $this;
    }

    public function pingAfter($url): IEvent
    {
        $this->event->thenPing($url);

        return $this;
    }
}
