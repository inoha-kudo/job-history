<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Entities;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final readonly class JobHistoryPeriod
{
    public function __construct(
        private ?CarbonImmutable $beginAt = null,
        private ?CarbonImmutable $endAt = null,
    ) {
        if ($endAt && ! $beginAt) {
            throw new InvalidArgumentException("'beginAt' must be set when 'endAt' is provided.");
        }
        if ($beginAt && $endAt && $beginAt->gt($endAt)) {
            throw new InvalidArgumentException("'beginAt' must be earlier than or equal to 'endAt'.");
        }
    }

    public function beginAt(): ?CarbonImmutable
    {
        return $this->beginAt;
    }

    public function endAt(): ?CarbonImmutable
    {
        return $this->endAt;
    }

    public function mark(bool $isBegin, bool $isEnd): self
    {
        return new self(
            beginAt: $isBegin ? CarbonImmutable::now() : $this->beginAt(),
            endAt: $isEnd ? CarbonImmutable::now() : $this->endAt(),
        );
    }
}
