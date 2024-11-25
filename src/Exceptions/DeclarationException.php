<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Exceptions;

class DeclarationException extends \RuntimeException
{
    public static function notSupported(string $type): self
    {
        return new self(sprintf('Unsupported type %s', $type));
    }

    public static function incompatbleLoan(): self
    {
        return new self('We can\'t guarantee loan calculation without blockType');
    }

}
