<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Tests\Unit\Entities;

use Miraiportal\JobHistory\Entities\JobHistory;
use Miraiportal\JobHistory\Entities\JobHistoryState;
use Miraiportal\JobHistory\Entities\JobId;
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
            JobHistoryState::Pending->value(),
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

    public function _test_change_state_to_running(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $jobHistory = $jobHistory->changeState(JobHistoryState::Running);

        $this->assertSame(
            JobHistoryState::Running->value(),
            $jobHistory->state(),
        );
        $this->assertNotNull($jobHistory->beginAt());
        $this->assertNull($jobHistory->endAt());
    }

    public function _test_change_state_to_succeeded(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $jobHistory = $jobHistory->changeState(JobHistoryState::Running);
        $jobHistory = $jobHistory->changeState(JobHistoryState::Succeeded);

        $this->assertSame(
            JobHistoryState::Succeeded->value(),
            $jobHistory->state(),
        );
        $this->assertNotNull($jobHistory->beginAt());
        $this->assertNotNull($jobHistory->endAt());
    }

    public function _test_change_state_to_failed(): void
    {
        $jobHistory = new JobHistory($this->jobId);

        $jobHistory = $jobHistory->changeState(JobHistoryState::Running);
        $jobHistory = $jobHistory->changeState(JobHistoryState::Failed);

        $this->assertSame(
            JobHistoryState::Failed->value(),
            $jobHistory->state(),
        );
        $this->assertNotNull($jobHistory->beginAt());
        $this->assertNotNull($jobHistory->endAt());
    }

    public function _test_change_state_with_params(): void
    {
        $jobHistory = new JobHistory(
            jobId: $this->jobId,
            params: ['state' => 0],
        );

        $this->assertSame(
            ['state' => 0],
            $jobHistory->params(),
        );

        $jobHistory = $jobHistory->changeState(JobHistoryState::Running);

        $this->assertSame(
            ['state' => 0],
            $jobHistory->params(),
        );

        $jobHistory = $jobHistory->changeState(JobHistoryState::Succeeded, ['state' => 2]);

        $this->assertSame(
            ['state' => 2],
            $jobHistory->params(),
        );
    }
}
