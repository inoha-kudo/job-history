<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Tests\Unit\Entities;

use Miraiportal\JobHistory\Entities\JobHistoryState;
use Miraiportal\JobState\Entities\JobFailedState;
use Miraiportal\JobState\Entities\JobPendingState;
use Miraiportal\JobState\Entities\JobRunningState;
use Miraiportal\JobState\Entities\JobSucceededState;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class JobHistoryStateTest extends TestCase
{
    public static function jobHistoryStateProvider(): array
    {
        return [
            [JobHistoryState::Pending, JobPendingState::class],
            [JobHistoryState::Running, JobRunningState::class],
            [JobHistoryState::Succeeded, JobSucceededState::class],
            [JobHistoryState::Failed, JobFailedState::class],
        ];
    }

    #[DataProvider('jobHistoryStateProvider')]
    public function test_create(JobHistoryState $state, string $expected): void
    {
        $this->assertInstanceOf(
            $expected,
            $state->create(),
        );
    }
}
