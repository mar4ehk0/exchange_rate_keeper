<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CurrencyRateRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repo = $this->entityManager->getRepository(CurrencyRate::class);
    }

    public function add(CurrencyRate $currencyRate): void
    {
        $this->entityManager->persist($currencyRate);
    }

    public function remove(CurrencyRate $currencyRate): void
    {
        $this->entityManager->remove($currencyRate);
    }
}
