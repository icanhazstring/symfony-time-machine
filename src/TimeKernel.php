<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine;

use DateTimeImmutable;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This time kernel will start a time machine for the applications kernel.
 * It is meant to be used when you want to test date time specific functionality.
 */
final class TimeKernel
{
    private bool $booted = false;
    private ?Request $request = null;
    /** @var array<array-key, mixed> */
    private array $context = [];

    public function __construct(private readonly KernelInterface $kernel)
    {
    }

    public function fromRequest(Request $request): self
    {
        $this->request = $request;
        $this->context = [];

        return $this;
    }

    /**
     * @param array<array-key, mixed> $context
     */
    public function fromContext(array $context): self
    {
        $this->context = $context;
        $this->request = null;

        return $this;
    }

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        /** @var null|string $timeMachineOffset */
        $timeMachineOffset = null;

        if ($this->request !== null) {
            $timeMachineOffset = $this->request->cookies->get('time-machine')
                ?? $this->request->query->get('time-machine');
        }

        if ($this->context !== []) {
            /** @var null|string $timeMachineOffset */
            $timeMachineOffset = $this->context['TIME_MACHINE'] ?? null;
        }

        if ($timeMachineOffset === null) {
            return;
        }

        $this->kernel->boot();

        $this->kernel->getContainer()->set(
            ClockInterface::class,
            new MockClock(new DateTimeImmutable((string) $timeMachineOffset))
        );

        $this->booted = true;
    }
}
