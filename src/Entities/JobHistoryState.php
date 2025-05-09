<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Entities;

use Miraiportal\JobState\Entities\JobFailedState;
use Miraiportal\JobState\Entities\JobPendingState;
use Miraiportal\JobState\Entities\JobRunningState;
use Miraiportal\JobState\Entities\JobState;
use Miraiportal\JobState\Entities\JobSucceededState;

enum JobHistoryState: int
{
    case Pending = JobState::PENDING;
    case Running = JobState::RUNNING;
    case Succeeded = JobState::SUCCEEDED;
    case Failed = JobState::FAILED;

    public function value(): int
    {
        return $this->value;
    }

    public function label(): string
    {
        return $this->toJobState()->label();
    }

    public function isBegin(): bool
    {
        return $this->toJobState()->isBegin();
    }

    public function isEnd(): bool
    {
        return $this->toJobState()->isEnd();
    }

    public function change(self $state): self
    {
        return self::from(
            $this->toJobState()->change($state->toJobState())->value(),
        );
    }

    private function toJobState(): JobState
    {
        return match ($this) {
            self::Pending => new JobPendingState,
            self::Running => new JobRunningState,
            self::Succeeded => new JobSucceededState,
            self::Failed => new JobFailedState,
        };
    }
}
