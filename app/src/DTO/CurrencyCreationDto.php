<?php

namespace App\DTO;

use App\Interface\JsonBodyDtoRequestInterface;

readonly class CurrencyCreationDto implements JsonBodyDtoRequestInterface
{
    public function __construct(
        public string $code,
        public string $char,
        public int $nominal,
        public string $humanName,
    ) {}
}
