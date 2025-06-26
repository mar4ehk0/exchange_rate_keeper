<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;

#[Entity()]
#[Table(name: 'currency')]
class Currency
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(type: Types::STRING, unique: true)]
    private string $code;

    #[ORM\Column(type: Types::STRING)]
    private string $char;

    #[ORM\Column(type: Types::INTEGER)]
    private int $nominal;

    #[ORM\Column(type: Types::STRING)]
    private string $humanName;

    public function __construct(
        string $code,
        string $char,
        int $nominal,
        string $humanName,
    ) {
        $this->code = $code;
        $this->char = $char;
        $this->nominal = $nominal;
        $this->humanName = $humanName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getChar(): string
    {
        return $this->char;
    }

    public function getNominal(): int
    {
        return $this->nominal;
    }

    public function getHumanName(): string
    {
        return $this->humanName;
    }
}
