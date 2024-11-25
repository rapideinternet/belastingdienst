<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self LOAN()
 * @method static self REVENUE()
 * 
 * @extends Enum<string>
 */
final class DeclarationTypes extends Enum
{
    public const LOAN = 'loan';
    public const REVENUE = 'revenue';
}
