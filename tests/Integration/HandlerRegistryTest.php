<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\Test\Integration;

use Icanhazstring\SymfonyTimeMachine\HandlerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class HandlerRegistryTest extends KernelTestCase
{
    /**
     * @test
     */
    public function itRegistryShouldHaveOneHandler(): void
    {
        /** @var HandlerRegistry $handlerRegistry */
        $handlerRegistry = self::getContainer()->get(HandlerRegistry::class);

        self::assertCount(1, $handlerRegistry->getHandlers());
    }
}
