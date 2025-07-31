<?php

namespace App\View;

use App\Entity\Currency;

readonly class CurrencyView
{
    public function __construct(private Currency $currency)
    {
    }

    public function getData(): array
    {
        return [
            'id' => $this->currency->getId(),
            'code' => $this->currency->getCode(),
            'char' => $this->currency->getChar(),
            'nominal' => $this->currency->getNominal(),
            'humanName' => $this->currency->getHumanName(),
        ];
    }
}
