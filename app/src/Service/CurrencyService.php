<?php

namespace App\Service;

use App\DTO\CurrencyCreationDto;
use App\Entity\Currency;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyService
{
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function createCurrency(CurrencyCreationDto $dto): Currency
    {
        // сделать запрос в бд через репозиторий если нету $dto->code то создаем иначе иключение

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
