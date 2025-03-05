<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Exceptions;

use RuntimeException;

class PeriodException extends RuntimeException
{
    public static function invalidPeriod(): self
    {
        return new self('Invalid period');
    }
}
