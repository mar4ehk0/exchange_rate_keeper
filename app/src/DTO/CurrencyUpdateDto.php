<?php

namespace App\DTO;

use App\Interface\JsonBodyDtoRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CurrencyUpdateDto implements JsonBodyDtoRequestInterface
{
    public function __construct(
        #[Assert\NotBlank]
        public int $id,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $code,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $char,
        #[Assert\NotBlank(allowNull: true)]
        public ?int $nominal,
        #[Assert\NotBlank(allowNull: true)]
        public ?string $humanName,
    ) {
    }
}
