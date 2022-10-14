<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\DependencyInjection;

use Icanhazstring\SymfonyTimeMachine\HandlerRegistry;
use Icanhazstring\SymfonyTimeMachine\TimeMachineHandler;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

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

        foreach ($container->getDefinitions() as $id => $definition) {
            if (!is_subclass_of($id, TimeMachineHandler::class)) {
                continue;
            }

            $handlerIds[] = $id;
        }

        $handlerRegistry = $container->register(HandlerRegistry::class)->setPublic(true);

        if (!empty($handlerIds)) {
            $handlerReferences = array_map(static fn(string $id) => new Reference($id), $handlerIds);
            $handlerRegistry->setArguments([$handlerReferences]);
        }
    }
}
