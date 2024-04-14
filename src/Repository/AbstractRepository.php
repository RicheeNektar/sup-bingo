<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        ManagerRegistry                         $registry,
        string                                  $entityClass,
    ) {
        parent::__construct($registry, $entityClass);
    }

    public abstract function create(mixed $data): mixed;

    public function delete(mixed $identifier): void
    {
        if ($identifier) {
            if (is_object($identifier)) {
                $entity = $identifier;
            } else {
                $entity = $this->find($identifier);
            }
        }

        if (!empty($entity)) {
            $this->entityManager->remove($entity);
        }
    }

    public function save(): void
    {
        $this->entityManager->flush();
    }
}