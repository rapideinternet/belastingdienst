<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Enums;

enum BlockTypes: string
{
    case MONTHLY = 'monthly';
    case FOURWEEK = 'fourweek';
    case QUARTER = 'quarter';
    case HALFYEAR = 'halfyear';
    case YEARLY = 'yearly';
}
