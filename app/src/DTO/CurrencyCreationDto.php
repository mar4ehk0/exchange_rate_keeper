<?php

namespace App\DTO;

readonly class CurrencyCreationDto
{
    public function __construct(
        public string $code,
        public string $char,
        public int $nominal,
        public string $humanName,
    ) {
    }
}
