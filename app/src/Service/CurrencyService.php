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

    public function getCurrencyByCode(string $code): ?Currency
    {
        return $this->currencyRepository->getByCode($code);
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

        if ($dto->code !== null) {
            $currency->setCode($dto->code);
        }
        if ($dto->char !== null) {
            $currency->setChar($dto->char);
        }
        if ($dto->nominal !== null) {
            $currency->setNominal($dto->nominal);
        }
        if ($dto->humanName !== null) {
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
