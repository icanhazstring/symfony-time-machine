<?php

declare(strict_types=1);

namespace Icanhazstring\SymfonyTimeMachine\Test\Integration;

use Icanhazstring\SymfonyTimeMachine\Test\App\TestClockService;
use Icanhazstring\SymfonyTimeMachine\TimeKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

final class TimeKernelTest extends KernelTestCase
{
    /**
     * @test
     */
    public function itShouldMockFromContext(): void
    {
        (new TimeKernel(self::bootKernel()))->fromContext(['TIME_MACHINE' => '2022-02-01 01:00:00'])->boot();

        /** @var TestClockService $testClockService */
        $testClockService = self::getContainer()->get(TestClockService::class);

        self::assertSame(
            '2022-02-01 01:00:00',
            $testClockService->getNow()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function itShouldMockFromRequestQuest(): void
    {
        $request = new Request(cookies: ['time-machine' => '2022-02-01 01:00:00']);
        (new TimeKernel(self::bootKernel()))->fromRequest($request)->boot();

        /** @var TestClockService $testClockService */
        $testClockService = self::getContainer()->get(TestClockService::class);

        self::assertSame(
            '2022-02-01 01:00:00',
            $testClockService->getNow()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function itShouldMockFromRequestCookie(): void
    {
        $request = new Request(query: ['time-machine' => '2022-02-01 01:00:00']);
        (new TimeKernel(self::bootKernel()))->fromRequest($request)->boot();

        /** @var TestClockService $testClockService */
        $testClockService = self::getContainer()->get(TestClockService::class);

        self::assertSame(
            '2022-02-01 01:00:00',
            $testClockService->getNow()->format('Y-m-d H:i:s')
        );
    }
}
