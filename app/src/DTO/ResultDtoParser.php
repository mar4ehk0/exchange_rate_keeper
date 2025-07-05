<?php

namespace App\DTO;

readonly class ResultDtoParser
{
    public function __construct(
        public string $code,
        public string $char,
        public int $nominal,
        public string $humanName,
        public string $valueRate,
    ) {
    }
}
