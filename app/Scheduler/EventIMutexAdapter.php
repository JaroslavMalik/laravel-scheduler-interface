<?php

namespace App\Scheduler;

use Illuminate\Console\Scheduling\EventMutex as EventMutexInterface;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\Exception\LockReleasingException;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\PersistingStoreInterface;

class EventIMutexAdapter implements IMutex
{
    protected EventMutexInterface $mutex;

    public function __construct(EventMutexInterface $mutex)
    {
        $this->mutex = $mutex;
    }

    public function create(IEvent $event): bool
    {
        return $this->mutex->create($event->getMutexEvent());
    }

    public function exists(IEvent $event): bool
    {
        return $this->mutex->exists($event->getMutexEvent());
    }

    public function delete(IEvent $event): void
    {
        $this->mutex->delete($event->getMutexEvent());
    }
}
