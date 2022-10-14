<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class HandlerRegistry
{
    /** @var iterable<TimeMachineHandler> */
    private iterable $handlers;

    /**
     * @param iterable<TimeMachineHandler> $handlers
     */
    public function __construct(
        #[TaggedIterator('timemachine.handler')] iterable $handlers = []
    ) {
        $this->handlers = $handlers;
    }

    /**
     * @return iterable<TimeMachineHandler>
     */
    public function getHandlers(): iterable
    {
        return $this->handlers;
    }
}
