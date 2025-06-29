<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyGetDto;

class CurrencyService
{
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getCurrencyById(CurrencyGetDto $dto): ?Currency
    {
        return $this->currencyRepository->getById($dto->id);
    }

    public function createCurrency(CurrencyCreationDto $dto): Currency
    {
        $currency = new Currency(
            $dto->code,
            $dto->char,
            $dto->nominal,
            $dto->humanName
        );

        $this->currencyRepository->add($currency);

        $this->entityManager->flush();

        return $currency;
    }

}
