<?php

declare(strict_types=1);

namespace Miraiportal\JobHistory\Entities;

use InvalidArgumentException;

final readonly class JobId
{
    private const int UINT8_MAX = 255;

    final public const int MIN = 1;

    final public const int MAX = self::UINT8_MAX;

    public function __construct(
        private int $value,
    ) {
        if ($value < self::MIN) {
            throw new InvalidArgumentException('The provided value must be greater than or equal to '.self::MIN.'.');
        }
        if ($value > self::MAX) {
            throw new InvalidArgumentException('The provided value must be less than or equal to '.self::MAX.'.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
