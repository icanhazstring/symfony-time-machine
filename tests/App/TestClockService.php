<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\Test\App;

use DateTimeImmutable;
use Symfony\Component\Clock\ClockInterface;

final class TestClockService
{
    public function __construct(private readonly ClockInterface $clock)
    {
    }

    public function getNow(): DateTimeImmutable
    {
        return $this->clock->now();
    }
}
