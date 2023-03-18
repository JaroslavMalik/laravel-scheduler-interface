<?php

namespace App\Scheduler;

use Illuminate\Console\Scheduling\Event;

class MutexEventMock extends Event
{
    protected string $mutexName;

    public function __construct(string $mutexName, int $expiresAt)
    {
        $this->mutexName = $mutexName;
        $this->expiresAt = $expiresAt;
    }

    public function mutexName(): string
    {
        return $this->mutexName;
    }
}
