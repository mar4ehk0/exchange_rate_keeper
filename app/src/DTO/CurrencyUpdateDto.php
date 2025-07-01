<?php

namespace App\DTO;

readonly class CurrencyUpdateDto
{
    public function __construct(
        public ?string $code = null,
        public ?string $char = null,
        public ?int $nominal  = null,
        public ?string $humanName = null,
    ){}

}
