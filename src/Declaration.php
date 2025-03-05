<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst;

use Carbon\Carbon;

class Declaration
{
    public function __construct(
        public string $declarationId,
        public string $paymentReference,
        public Carbon $paymentDueDate
    )
    {}
}
