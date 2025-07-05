<?php

namespace App\DTO;

readonly class CBRFHttpClientResultDto
{
    public function __construct(
        public string $contentType,
        public string $rawContent,
    ) {
    }
}
