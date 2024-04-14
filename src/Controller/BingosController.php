<?php

namespace App\Controller;

use App\Form\CreateBingoType;
use App\Repository\BingoRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Attribute\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

final class BingosController extends AbstractController
{
    public function __construct(
        private readonly BingoRepository $bingoRepository,
    ) {
    }

    #[Route(path: '/bingo', name: 'bingo_list')]
    public function list(Session $session): Response
    {
        $createBingo = $this->createForm(CreateBingoType::class);

        return $this->render(
            'bingos.html.twig',
            [
                'bingos' => $this->bingoRepository->findAll(),
                'create_bingo' => $createBingo,
            ]
        );
    }

    #[Route(path: '/bingo/create', name: 'bingo_create', methods: Request::METHOD_POST)]
    public function create(Request $request): Response
    {
        $createBingo = $this->createForm(CreateBingoType::class);
        $createBingo->handleRequest($request);

        if ($createBingo->isSubmitted() && $createBingo->isValid()) {
            $this->bingoRepository->create($createBingo->getData()['name']);
            $this->bingoRepository->save();
        }

        return $this->redirectToRoute('bingo_list');
    }

    #[Route(path: '/bingo/delete', name: 'bingo_delete', methods: Request::METHOD_POST)]
    public function delete(Request $request): Response
    {
        $this->bingoRepository->delete($request->request->get('bingo'));
        $this->bingoRepository->save();
        return $this->redirectToRoute('bingo_list');
    }
}