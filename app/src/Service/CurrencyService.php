<?php

namespace App\Service;

use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;

class CurrencyService
{
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getCurrencyById(int $id): ?Currency
    {
        return $this->currencyRepository->getById($id);
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

    public function updateCurrency(int $id, CurrencyUpdateDto $dto): ?Currency
    {
        $currency = $this->getCurrencyById($id);

        if (!$currency instanceof Currency) return null;

        $dto->code? $currency->setCode($dto->code) : null;
        $dto->char ? $currency->setChar($dto->char) : null;
        $dto->nominal ? $currency->setNominal($dto->nominal) : null;
        $dto->humanName ? $currency->setHumanName($dto->humanName) : null;

        $this->entityManager->flush();

        return $currency;
    }

    public function deleteCurrency(int $id): bool
    {
        $currency = $this->getCurrencyById($id);

        if (!$currency instanceof Currency) return false;

        $this->entityManager->remove($currency);

        $this->entityManager->flush();

        return true;
    }
}
