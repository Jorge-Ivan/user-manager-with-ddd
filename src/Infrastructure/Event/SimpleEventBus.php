<?php
namespace UserManager\Infrastructure\Event;

use UserManager\Application\Event\EventBus;
use UserManager\Application\Event\EventHandler;
use UserManager\Domain\Event\DomainEvent;

final class SimpleEventBus implements EventBus
{
    private array $handlers = [];

    public function dispatch(DomainEvent $event): void
    {
        $eventClassName = get_class($event);
        
        if (!isset($this->handlers[$eventClassName])) {
            return;
        }
        
        foreach ($this->handlers[$eventClassName] as $handler) {
            $handler->handle($event);
        }
    }

    public function register(string $eventClassName, EventHandler $handler): void
    {
        if (!isset($this->handlers[$eventClassName])) {
            $this->handlers[$eventClassName] = [];
        }
        
        $this->handlers[$eventClassName][] = $handler;
    }
}