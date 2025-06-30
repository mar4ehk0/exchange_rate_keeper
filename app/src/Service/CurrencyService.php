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

    public function updateCurrency(int $id, CurrencyUpdateDto $updateDto): ?Currency
    {
        $currency = $this->getCurrencyById($id);

        if (!$currency) return null;

        $currency->update($updateDto);

        $this->entityManager->flush();

        return $currency;
    }

    public function deleteCurrency(int $id): mixed
    {
        $currency = $this->getCurrencyById($id);

        if (!$currency) return null;

        $this->entityManager->remove($currency);

        $this->entityManager->flush();

        return true;
    }
}
