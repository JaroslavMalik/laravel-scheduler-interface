<?php

namespace App\Scheduler;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\EventMutex;

class EventMutexAdapter implements EventMutex
{
    protected IMutex $mutex;
    protected IEvent $event;

    public function __construct(IMutex $mutex, IEvent $event)
    {
        $this->mutex = $mutex;
        $this->event = $event;
    }

    public function create(Event $event): bool
    {
        return $this->mutex->create($this->event);
    }

    public function exists(Event $event): bool
    {
        return $this->mutex->exists($this->event);
    }

    public function forget(Event $event): void
    {
        $this->mutex->delete($this->event);
    }


}
