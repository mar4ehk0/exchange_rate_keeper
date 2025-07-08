<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CurrencyRepository
{
    // сделать базовый класс который будет создавать сам репозиторий как в конструкторе на основе entity

    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->repo = $this->entityManager->getRepository(Currency::class);
    }

    public function add(Currency $currency): void
    {
        $this->entityManager->persist($currency);
    }

    public function remove(Currency $currency): void
    {
        $this->entityManager->remove($currency);
    }

    public function getById(int $id): ?Currency
    {
        // []
        return $this->repo->find($id);
    }

    public function getByCode(string $code): ?Currency
    {
        return $this->repo->findOneBy(['code' => $code]);
    }

    public function getAll(): array
    {
        return $this->repo->findAll();
    }
}
