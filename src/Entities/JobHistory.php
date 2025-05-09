<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Entities;

use Carbon\CarbonImmutable;

final readonly class JobHistory
{
    /** @param ?array<string, mixed> $params */
    private function __construct(
        private ?string $id,
        private JobId $jobId,
        private JobHistoryState $state,
        private ?array $params,
        private JobHistoryPeriod $period,
    ) {}

    /** @param ?array<string, mixed> $params */
    public static function create(
        JobId $jobId,
        ?string $id = null,
        JobHistoryState $state = JobHistoryState::Pending,
        ?array $params = null,
        ?CarbonImmutable $beginAt = null,
        ?CarbonImmutable $endAt = null,
    ): self {
        return new self(
            id: $id,
            jobId: $jobId,
            state: $state,
            params: $params,
            period: new JobHistoryPeriod($beginAt, $endAt),
        );
    }

    /** @param ?array<string, mixed> $params */
    public function withState(JobHistoryState $state, ?array $params = null): self
    {
        return new self(
            id: $this->id,
            jobId: $this->jobId,
            state: $changedState = $this->state->change($state),
            params: $params ?? $this->params,
            period: $this->period->mark($changedState->isBegin(), $changedState->isEnd()),
        );
    }

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
}
