<?php
namespace UserManager\Application\Event;

use UserManager\Domain\Event\DomainEvent;

interface EventBus
{
    public function dispatch(DomainEvent $event): void;
    public function register(string $eventClassName, EventHandler $handler): void;
}