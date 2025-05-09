<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Tests\Unit\Entities;

use Miraiportal\JobHistory\Entities\JobHistoryState;
use Miraiportal\JobState\Entities\JobFailedState;
use Miraiportal\JobState\Entities\JobPendingState;
use Miraiportal\JobState\Entities\JobRunningState;
use Miraiportal\JobState\Entities\JobSucceededState;
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

    public function test_value(): void
    {
        $this->assertTrue(true);
    }

    public function test_is_begin(): void
    {
        $this->assertTrue(true);
    }

    public function test_is_end(): void
    {
        $this->assertTrue(true);
    }

    public function test_change(): void
    {
        $this->assertTrue(true);
    }
}
