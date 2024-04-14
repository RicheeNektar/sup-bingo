<?php

namespace App\Controller;

use App\Entity\GameText;
use App\Repository\BingoRepository;
use App\Repository\GameRepository;
use App\Repository\GameTextRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GameController extends AbstractController
{
    public function __construct(
        private readonly BingoRepository $bingoRepository,
        private readonly GameRepository $gameRepository,
        private readonly GameTextRepository $gameTextRepository,
    ) {
    }

    #[Route(path: '/', name: 'game')]
    public function game(): Response
    {
        $game = $this->getUser()->getGame();

        return $this->render(
            'game.html.twig',
            [
                'game' => $game,
                'game_texts' => $game?->getGameTexts() ?? [],
                'bingo' => $game && $this->gameRepository->calculateBingo($game),
            ]
        );
    }

    #[Route(path: '/game/{gameText<\d+>}/toggle', name: 'game_toggle_field')]
    public function toggle(GameText $gameText): Response
    {
        $gameText->setActive(!$gameText->isActive());
        $this->gameTextRepository->save();
        $state = $gameText->isActive();

        return $this->json([
            'state' => $state,
            'bingo' => $this->gameRepository->calculateBingo(
                $gameText->getGame()
            )
        ]);
    }

    #[Route(path: '/create', name: 'game_create', methods: [Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        $oldGame = $user->getGame();
        if ($oldGame) {
            if ($this->gameRepository->calculateBingo($oldGame)) {
                $user->setPoints($user->getPoints() + 1);
            }
            $this->gameRepository->delete($oldGame);
        }

        $bingo = $this->bingoRepository->find($request->request->get('bingo'));
        if ($bingo) {
            $bingoTexts = $bingo->getBingoTexts()->toArray();

            if (count($bingoTexts) >= 25) {
                $game = $this->gameRepository->create($user);
                shuffle($bingoTexts);

                foreach (array_slice($bingoTexts, 0, 25) as $bingoText) {
                    $this->gameTextRepository->create([
                        'bingoText' => $bingoText,
                        'game' => $game,
                    ]);
                }

                $this->gameRepository->save();
            }
        }

        return $this->redirectToRoute('game');
    }

    #[Route(path: '/reset', name: 'game_reset', methods: [Request::METHOD_GET])]
    public function reset(): Response
    {
        $user = $this->getUser();
        $oldGame = $user->getGame();

        if ($oldGame) {
            if ($this->gameRepository->calculateBingo($oldGame)) {
                $user->setPoints($user->getPoints() + 1);
            }
            $user->setGame(null);
            $this->gameRepository->delete($oldGame);
            $this->gameRepository->save();
        }

        return $this->redirectToRoute('game');
    }
}