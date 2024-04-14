<?php

namespace App\Repository;

use App\Entity\GameText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class GameTextRepository extends AbstractRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry
    ) {
        parent::__construct($entityManager, $registry, GameText::class);
    }

    public function create(mixed $data): GameText
    {
        ($gameText = new GameText())
            ->setBingoText($data['bingoText']);

        $data['game']->addGameText($gameText);

        $this->getEntityManager()->persist($gameText);
        return $gameText;
    }
}
