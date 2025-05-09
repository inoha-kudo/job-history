<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Tests\Unit\Entities;

use Miraiportal\JobHistory\Entities\JobHistory;
use Miraiportal\JobHistory\Entities\JobId;
use Miraiportal\JobState\Entities\JobFailedState;
use Miraiportal\JobState\Entities\JobRunningState;
use Miraiportal\JobState\Entities\JobState;
use Miraiportal\JobState\Entities\JobSucceededState;
use PHPUnit\Framework\TestCase;

final class JobHistoryTest extends TestCase
{
    private readonly JobId $jobId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jobId = new JobId(1);
    }

    public function test_values(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $this->assertNull($jobHistory->id());
        $this->assertSame(
            1,
            $jobHistory->jobId(),
        );
        $this->assertSame(
            JobState::PENDING,
            $jobHistory->state(),
        );
        $this->assertNull($jobHistory->params());
        $this->assertNull($jobHistory->beginAt());
        $this->assertNull($jobHistory->endAt());
    }

    public function test_is_processing(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $this->assertTrue($jobHistory->isProcessing());
    }

    public function test_change_state_to_running(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $jobHistory = $jobHistory->changeState(new JobRunningState);

        $this->assertSame(
            JobState::RUNNING,
            $jobHistory->state(),
        );
        $this->assertNotNull($jobHistory->beginAt());
        $this->assertNull($jobHistory->endAt());
    }

    public function test_change_state_to_succeeded(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $jobHistory = $jobHistory->changeState(new JobRunningState);
        $jobHistory = $jobHistory->changeState(new JobSucceededState);

        $this->assertSame(
            JobState::SUCCEEDED,
            $jobHistory->state(),
        );
        $this->assertNotNull($jobHistory->beginAt());
        $this->assertNotNull($jobHistory->endAt());
    }

    public function test_change_state_to_failed(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $jobHistory = $jobHistory->changeState(new JobRunningState);
        $jobHistory = $jobHistory->changeState(new JobFailedState);

        $this->assertSame(
            JobState::FAILED,
            $jobHistory->state(),
        );
        $this->assertNotNull($jobHistory->beginAt());
        $this->assertNotNull($jobHistory->endAt());
    }

    public function test_change_state_with_params(): void
    {
        $jobHistory = new JobHistory(
            jobId: $this->jobId,
            params: ['state' => 0],
        );

        $this->assertSame(
            ['state' => 0],
            $jobHistory->params(),
        );

        $jobHistory = $jobHistory->changeState(new JobRunningState);

        $this->assertSame(
            ['state' => 0],
            $jobHistory->params(),
        );

        $jobHistory = $jobHistory->changeState(new JobSucceededState, ['state' => 2]);

        $this->assertSame(
            ['state' => 2],
            $jobHistory->params(),
        );
    }
}
