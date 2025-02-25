<?php
namespace UserManager\Application\Event;

use UserManager\Domain\Event\DomainEvent;
use UserManager\Domain\Event\UserRegisteredEvent;

final class WelcomeEmailSender implements EventHandler
{
    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof UserRegisteredEvent) {
            return;
        }

        echo sprintf(
            "//Fake sending welcome email to %s (%s)\n",
            $event->name(),
            $event->email()
        );
    }
}