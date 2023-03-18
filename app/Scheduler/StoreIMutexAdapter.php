<?php

namespace App\Scheduler;

use Symfony\Component\Lock\Exception\LockAcquiringException;
use Symfony\Component\Lock\Exception\LockConflictedException;
use Symfony\Component\Lock\Exception\LockReleasingException;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\PersistingStoreInterface;

class StoreIMutexAdapter implements IMutex
{
    protected PersistingStoreInterface $store;

    public function __construct(PersistingStoreInterface $store)
    {
        $this->store = $store;
    }

    public function create(IEvent $event): bool
    {
        try {
            $this->store->save($event->getMutexName());
        } catch (LockConflictedException $exception) {
            return false;
        } catch (LockAcquiringException $exception) {
            return false;
        }

        return true;
    }

    public function exists(IEvent $event): bool
    {
        return $this->store->exists($event->getMutexName());
    }

    public function delete(IEvent $event): void
    {
        $this->store->delete($event->getMutexName());
    }
}
