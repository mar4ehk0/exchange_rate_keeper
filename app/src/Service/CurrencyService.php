<?php

namespace App\Service;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Exception\CurrencyAlreadyExistsException;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

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

    public function getCurrencyByCode(string $code): ?Currency
    {
        return $this->currencyRepository->getByCode($code);
    }

    public function createCurrency(CurrencyCreationDto $dto): Currency
    {
        // сделатьт запрос есть ли уникальный code, если есть то кинуть исключение.
        $currency = $this->getCurrencyByCode($dto->code);
        if ($currency instanceof Currency) {
            throw new CurrencyAlreadyExistsException("Currency with code '{$dto->code}' already exists");
        }

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

    public function updateCurrency(CurrencyUpdateDto $dto): ?Currency
    {
        $currency = $this->getCurrencyById($dto->id);
        if (!$currency instanceof Currency) {
            return null;
        }
        $existingCurrency = $this->getCurrencyByCode($dto->code);
        if ($existingCurrency && $existingCurrency->getId() !== $currency->getId()) {
            throw new CurrencyAlreadyExistsException("Currency with code '{$dto->code}' already exists. Please choose a unique code.");
        }

        if (null !== $dto->code) {
            $currency->setCode($dto->code);
        }
        if (null !== $dto->char) {
            $currency->setChar($dto->char);
        }
        if (null !== $dto->nominal) {
            $currency->setNominal($dto->nominal);
        }
        if (null !== $dto->humanName) {
            $currency->setHumanName($dto->humanName);
        }

        $this->entityManager->flush();

        return $currency;
    }

    public function deleteCurrency(int $id): bool
    {
        $currency = $this->getCurrencyById($id);

        if (!$currency instanceof Currency) {
            return false;
        }

        $this->entityManager->remove($currency);

        $this->entityManager->flush();

        return true;
    }

    public function getAllCurrencies(): array
    {
        return $this->currencyRepository->getAll();
    }
}
