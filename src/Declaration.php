<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst;


class Declaration
{
    public $declarationId;
    public $paymentReference;
    public $paymentDueDate;

    public function __construct($declarationId, $paymentReference, $paymentDueDate)
    {

        $this->declarationId = $declarationId;
        $this->paymentReference = $paymentReference;
        $this->paymentDueDate = $paymentDueDate;
    }
}
