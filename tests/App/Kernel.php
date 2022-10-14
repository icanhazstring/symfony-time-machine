<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\Test\App;

use Icanhazstring\SymfonyTimeMachine\TimeMachineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TimeMachineBundle()
        ];
    }
}
