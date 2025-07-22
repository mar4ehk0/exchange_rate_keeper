<?php

namespace App\DTO;

use DateTimeImmutable;
use App\Entity\Currency;

readonly class CurrencyRateCreationDto
{
    public function __construct(
        public Currency $currency,
        public string $value,
        public DateTimeImmutable $datetimeRate
    ){}
}
