<?php

namespace App\Scheduler;

use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\Exception\LockReleasingException;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\PersistingStoreInterface;

class PersistingStoreAdapter implements PersistingStoreInterface
{
    protected IMutex $mutex;
    protected IEvent $event;

    public function __construct(IMutex $mutex, IEvent $event)
    {
        $this->mutex = $mutex;
        $this->event = $event;
    }

    public function save(Key $key): void
    {
        $isCreated = $this->mutex->create($this->event);
        if (!$isCreated) {
            throw new LockConflictedException();
        }
    }

    public function delete(Key $key): void
    {
        $this->mutex->delete($this->event);
    }

    public function exists(Key $key): bool
    {
        return $this->mutex->exists($this->event);
    }

    public function putOffExpiration(Key $key, float $ttl)
    {
        // do nothing
    }
}
