<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static self MONTHLY()
 * @method static self FOURWEEK()
 * @method static self QUARTER()
 * @method static self HALFYEAR()
 * @method static self YEARLY()

 *
 * @extends Enum<string>
 */
final class BlockTypes extends Enum
{
    public const MONTHLY = 'monthly';
    public const FOURWEEK = 'fourweek';
    public const QUARTER = 'quarter';
    public const HALFYEAR = 'halfyear';
    public const YEARLY = 'yearly';
}
