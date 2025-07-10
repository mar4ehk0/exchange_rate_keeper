<?php

namespace App\Service;

use App\DTO\CurrencyRateCreationDto;
use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyRateService
{
    public function __construct(
        private CurrencyRateRepository $currencyRateRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function createCurrencyRate(CurrencyRateCreationDto $dto): CurrencyRate
    {
        $currencyRate = new CurrencyRate(
            $dto->currency,
            $dto->datetimeRate,
            $dto->value
        );

        $this->currencyRateRepository->add($currencyRate);

        $this->entityManager->flush();

        return $currencyRate;
    }
}
