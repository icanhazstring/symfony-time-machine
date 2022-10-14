<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\DependencyInjection;

use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

final class TimeMachineExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
    }

    public function prepend(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(ClockInterface::class)) {
            return;
        }

        $container->getDefinition(ClockInterface::class)->setPublic(true);
    }
}
