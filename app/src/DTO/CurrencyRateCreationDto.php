<?php

namespace App\DTO;

use App\Entity\Currency;

readonly class CurrencyRateCreationDto
{
    public function __construct(
        public Currency $currency,
        public string $value,
        public \DateTimeImmutable $datetimeRate,
    ) {
    }
}
