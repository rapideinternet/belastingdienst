<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Exceptions;

use Mijnkantoor\Belastingdienst\Enums\DeclarationTypes;
use RuntimeException;

class DeclarationException extends RuntimeException
{
    public static function notSupported(DeclarationTypes $type): self
    {
        return new self(sprintf('Unsupported type %s', $type->value));
    }

    public static function incompatibleLoan(): self
    {
        return new self('We can\'t guarantee loan calculation without blockType');
    }

    public static function invalidDeclarationId(): self
    {
        return new self('Could not extract stripped declaration id');
    }
}
