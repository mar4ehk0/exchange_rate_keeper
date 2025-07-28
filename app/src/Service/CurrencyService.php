<?php

namespace App\Service;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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

        //        как то это я пропустил. так не делай. делай явно
        //        $dto->code ? $currency->setCode($dto->code) : null;
        //        $dto->char ? $currency->setChar($dto->char) : null;
        //        $dto->nominal ? $currency->setNominal($dto->nominal) : null;
        //        $dto->humanName ? $currency->setHumanName($dto->humanName) : null;

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

    public function deleteCurrency(int $id): void
    {
        $currency = $this->getCurrencyById($id);

        if (!$currency instanceof Currency) {
            return false; // вместо bool должно кидать исключение. не найден notfoundExceptioon
        }

        try {
            $this->entityManager->remove($currency);

            $this->entityManager->flush();
        } catch (Exception $e) {
            // создай свое исключение NotDeleteCurrencyException и если есть секция catch то заворачивай все в это исключение
        }
    }

    public function getAllCurrencies(): array
    {
        return $this->currencyRepository->getAll();
    }
}
