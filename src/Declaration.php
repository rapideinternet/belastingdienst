<?php

declare(strict_types=1);

namespace Mijnkantoor\Belastingdienst;

use Carbon\Carbon;

class Declaration
{
    public const string IBAN = 'NL86INGB0002445588';

    public function __construct(
        public string $declarationId,
        public string $paymentReference,
        public Carbon $paymentDueDate
    ) {}

    public function getFormattedIban(): string
    {
        return sprintf(
            '%2s %2d %4s %4s %4s %2s',
            substr(self::IBAN, 0, 2),
            substr(self::IBAN, 2, 2),
            substr(self::IBAN, 4, 4),
            substr(self::IBAN, 8, 4),
            substr(self::IBAN, 12, 4),
            substr(self::IBAN, 16, 2),
        );
    }

    public function getFormattedPaymentReference(): string
    {
        return implode('.', str_split($this->paymentReference, 4));
    }
}
