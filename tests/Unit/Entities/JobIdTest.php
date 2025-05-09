<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Tests\Unit\Entities;

use InvalidArgumentException;
use Miraiportal\JobHistory\Entities\JobId;
use PHPUnit\Framework\TestCase;

final class JobIdTest extends TestCase
{
    public function test_instantiate_with_value_at_min(): void
    {
        $this->assertInstanceOf(
            JobId::class,
            new JobId(JobId::MIN),
        );
    }

    public function test_instantiate_with_value_at_max(): void
    {
        $this->assertInstanceOf(
            JobId::class,
            new JobId(JobId::MAX),
        );
    }

    public function test_instantiate_with_value_less_than_min(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided value must be greater than or equal to '.JobId::MIN.'.');

        new JobId(JobId::MIN - 1);
    }

    public function test_instantiate_with_value_greater_than_max(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The provided value must be less than or equal to '.JobId::MAX.'.');

        new JobId(JobId::MAX + 1);
    }

    public function test_value(): void
    {
        $this->assertSame(
            1,
            new JobId(1)->value(),
        );
    }
}
