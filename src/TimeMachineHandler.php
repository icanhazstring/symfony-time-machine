<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine;

/**
 * Marker interface for handlers which get executed after the ClockInterface
 * was changed during TimeMachine boot.
 *
 * These services can be used to further change behavior of you application in time-machine mode.
 */
interface TimeMachineHandler
{
    /**
     * Gets called after the ClockInterface was overridden.
     */
    public function handle(): void;
}
