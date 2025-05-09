<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Entities;

use Carbon\CarbonImmutable;

final readonly class JobHistory
{
    public function __construct(
        private JobId $jobId,
        private ?string $id = null,
        private JobHistoryState $state = JobHistoryState::Pending,
        /** @var ?array<string, mixed> */
        private ?array $params = null,
        private JobHistoryPeriod $period = new JobHistoryPeriod,
    ) {}

    public function id(): ?string
    {
        return $this->id;
    }

    public function jobId(): int
    {
        return $this->jobId->value();
    }

    public function state(): int
    {
        return $this->state->value();
    }

    /** @return ?array<string, mixed> */
    public function params(): ?array
    {
        return $this->params;
    }

    public function beginAt(): ?CarbonImmutable
    {
        return $this->period->beginAt();
    }

    public function endAt(): ?CarbonImmutable
    {
        return $this->period->endAt();
    }

    public function isProcessing(): bool
    {
        return ! $this->state->isEnd();
    }

    /** @param ?array<string, mixed> $params */
    public function changeState(JobHistoryState $state, ?array $params = null): self
    {
        return new self(
            jobId: $this->jobId,
            id: $this->id,
            state: $changedState = $this->state->change($state),
            params: $params ?? $this->params,
            period: $this->period->mark($changedState->isBegin(), $changedState->isEnd()),
        );
    }
}
