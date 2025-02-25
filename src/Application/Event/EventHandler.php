<?php
namespace UserManager\Application\Event;

use UserManager\Domain\Event\DomainEvent;

interface EventHandler
{
    public function handle(DomainEvent $event): void;
}