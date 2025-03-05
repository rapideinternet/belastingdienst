<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst\Enums;

enum DeclarationTypes: string
{
    case LOAN = 'loan';
    case REVENUE = 'revenue';
}
