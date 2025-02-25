<?php
namespace UserManager\Domain\Event;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}