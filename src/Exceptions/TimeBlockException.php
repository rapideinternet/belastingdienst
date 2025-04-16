<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Exceptions;

use RuntimeException;

class TimeBlockException extends RuntimeException
{
    public static function invalidYear(int $year): self
    {
        return new self(sprintf('Invalid year %s', $year));
    }

}
