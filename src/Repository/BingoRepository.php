<?php

namespace App\Repository;

use App\Entity\Bingo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

final class BingoRepository extends AbstractRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry
    ) {
        parent::__construct($entityManager, $registry, Bingo::class);
    }

    public function create(mixed $data): Bingo
    {
        ($bingo = new Bingo())
            ->setName($data);

        $this->getEntityManager()->persist($bingo);
        return $bingo;
    }
}