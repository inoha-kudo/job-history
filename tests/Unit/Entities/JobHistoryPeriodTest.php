<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Tests\Unit\Entities;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Miraiportal\JobHistory\Entities\JobHistoryPeriod;
use PHPUnit\Framework\TestCase;

final class JobHistoryPeriodTest extends TestCase
{
    private readonly CarbonImmutable $beginAt;

    private readonly CarbonImmutable $endAt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->beginAt = CarbonImmutable::now();
        $this->endAt = $this->beginAt->copy();
    }

    public function test_instantiate_with_only_begin_at(): void
    {
        $this->assertInstanceOf(
            JobHistoryPeriod::class,
            new JobHistoryPeriod(beginAt: $this->beginAt),
        );
    }

    public function test_instantiate_with_begin_at_earlier_than_end_at(): void
    {
        $this->assertInstanceOf(
            JobHistoryPeriod::class,
            new JobHistoryPeriod(
                beginAt: $this->beginAt,
                endAt: $this->endAt->addSecond(),
            ),
        );
    }

    public function test_instantiate_with_begin_at_equal_to_end_at(): void
    {
        $this->assertInstanceOf(
            JobHistoryPeriod::class,
            new JobHistoryPeriod(
                beginAt: $this->beginAt,
                endAt: $this->endAt,
            ),
        );
    }

    public function test_instantiate_with_end_at_without_begin_at(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'beginAt' must be set when 'endAt' is provided.");

        new JobHistoryPeriod(endAt: $this->endAt);
    }

    public function test_instantiate_with_begin_at_later_than_end_at(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The provided 'beginAt' must be earlier than or equal to 'endAt'.");

        new JobHistoryPeriod(
            beginAt: $this->beginAt,
            endAt: $this->endAt->subSecond(),
        );
    }

    public function test_values(): void
    {
        $period = new JobHistoryPeriod(
            beginAt: $this->beginAt,
            endAt: $this->endAt,
        );

        $this->assertSame(
            $this->beginAt,
            $period->beginAt(),
        );

        $this->assertSame(
            $this->endAt,
            $period->endAt(),
        );
    }
}
