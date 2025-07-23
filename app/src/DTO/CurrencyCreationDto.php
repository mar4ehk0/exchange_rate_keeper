<?php

namespace App\DTO;

use App\Interface\JsonBodyDtoRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CurrencyCreationDto implements JsonBodyDtoRequestInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public string $code,
        #[Assert\NotBlank]
        public string $char,
        #[Assert\NotBlank]
        public int $nominal,
        #[Assert\NotBlank]
        public string $humanName,
    ) {
    }
}
