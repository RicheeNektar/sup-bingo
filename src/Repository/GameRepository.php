<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

class GameRepository extends AbstractRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry
    ) {
        parent::__construct($entityManager, $registry, Game::class);
    }

    public function create(mixed $data): Game
    {
        ($game = new Game())
            ->setUser($data);

        $this->getEntityManager()->persist($game);
        return $game;
    }

    public function calculateBingo(Game $game): bool
    {
        $texts = $game->getGameTexts();

        $xPoints = 0;
        $yPoints = [0,0,0,0,0];
        $bingo = false;
        $diag = [0,0];

        for ($i = 0; $i < 25; $i++) {
            $x = $i % 5;

            if ($x === 0) {
                if ($bingo = $xPoints === 5) {
                    break;
                }
                $xPoints = 0;
            }

            $p = (int)$texts[$i]->isActive();
            $yPoints[$x] += $p;
            $xPoints += $p;

            if ($i % 6 === 0) {
                $diag[0] += $p;
            } else if ($i % 4 === 0 && $i > 1 && $i < 24) {
                $diag[1] += $p;
            }
        }

        if (!$bingo) {
            $bingo = $diag[0] === 5 || $diag[1] === 5;

            if (!$bingo) {
                for ($y = 0; $y < 5; $y++) {
                    if ($bingo = $yPoints[$y] === 5) {
                        break;
                    }
                }
            }
        }

        return $bingo;
    }
}
