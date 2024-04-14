<?php

namespace App\Repository;

use App\Entity\BingoText;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class BingoTextRepository extends AbstractRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry
    ) {
        parent::__construct($entityManager, $registry, BingoText::class);
    }

    public function create(mixed $data): BingoText
    {
        ($text = new BingoText())
            ->setBingo($data['bingo'])
            ->setText($data['text']);

        $this->getEntityManager()->persist($text);
        return $text;
    }
}
