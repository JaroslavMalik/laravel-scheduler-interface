<?php

namespace App\Scheduler;

use Closure;
use Crunz\Application\Service\ClosureSerializerInterface;
use Crunz\Event;
use Crunz\Infrastructure\Laravel\LaravelClosureSerializer;
use DateTimeZone;
use Illuminate\Console\Scheduling\Event as MutexEvent;

class CrunzEventAdapter implements IEvent
{
    protected static LaravelClosureSerializer $closureSerializer;

    protected Event $event;

    protected int $expiresAt = 1440;

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
        if ($mutex === null) {
            $this->event->preventOverlapping();

            return $this;
        }

        $this->expiresAt = $ttl;
        $persistingStore = new PersistingStoreAdapter($mutex, $this);
        $this->event->preventOverlapping($persistingStore);

        return $this;
    }

    public function getMutexName(): string
    {
        if ($this->event->isClosure()) {
            /** @var \Closure $closure */
            $closure = $this->event->getCommand();
            $command = $this->getClosureSerializer()
                ->closureCode($closure)
            ;
        } else {
            $command = $this->event->buildCommand();
        }

        return 'crunz-' . \md5($command);
    }

    public function getMutexEvent(): MutexEvent
    {
        return new MutexEventMock($this->getMutexName(), $this->getMutexTTL());
    }

    public function getMutexTTL(): int
    {
        return $this->expiresAt;
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

    protected function getClosureSerializer(): ClosureSerializerInterface
    {
        if (null === self::$closureSerializer) {
            self::$closureSerializer = new LaravelClosureSerializer();
        }

        return self::$closureSerializer;
    }
}
