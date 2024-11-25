<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Exceptions;

class TimeBlockException extends \RuntimeException
{
    public static function invalidYear(int $year): self
    {
        return new self(sprintf('Invalid year %d', $year));
    }

    public static function noPeriodCodeForRevenue(): self
    {
        return new self('No period code for revenue');
    }

    public static function noPeriodCodeForLoan(): self
    {
        return new self('No period code for loan');
    }
}
