<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table(name: 'currency')]
class Currency // пользователь, админ // руб, доллар, суммы, тенге, евро
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

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setChar(string $char): void
    {
        $this->char = $char;
    }

    public function setNominal(int $nominal): void
    {
        $this->nominal = $nominal;
    }

    public function setHumanName(string $humanName): void
    {
        $this->humanName = $humanName;
    }
}
