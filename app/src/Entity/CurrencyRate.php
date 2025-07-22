<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;

#[Entity()]
#[Table(name: 'currency_rate')]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;


    #[ORM\ManyToOne(targetEntity: Currency::class)]
    #[ORM\JoinColumn(name: 'currency_id', referencedColumnName: 'id', nullable: false)]
    private Currency $currency;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $value;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $datetimeRate;

    public function __construct(
        Currency $currency,
        DateTimeImmutable $datetimeRate,
        string $value
    ) {
        $this->datetimeRate = $datetimeRate;
        $this->currency = $currency;
        $this->value = $value;
    }

    public function getDatetimeRate(): DateTimeImmutable
    {
        return $this->datetimeRate;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
