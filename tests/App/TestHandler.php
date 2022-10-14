<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\Test\App;

use Icanhazstring\SymfonyTimeMachine\TimeMachineHandler;

final class TestHandler implements TimeMachineHandler
{
    private CallSpy $spy;

    public function handle(): void
    {
        $this->spy->called = true;
    }

    public function setSpy(CallSpy $callSpy): void
    {
        $this->spy = $callSpy;
    }
}
