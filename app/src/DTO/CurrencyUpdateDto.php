<?php

namespace App\DTO;

use App\Interface\JsonBodyDtoRequestInterface;

readonly class CurrencyUpdateDto implements JsonBodyDtoRequestInterface
{
    public function __construct(
        public int $id,
        public ?string $code = null,
        public ?string $char = null,
        public ?int $nominal = null,
        public ?string $humanName = null,
    ) {
    }
}
