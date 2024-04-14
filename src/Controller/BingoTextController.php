<?php

namespace App\Controller;

use App\Entity\Bingo;
use App\Form\CreateBingoTextType;
use App\Repository\BingoTextRepository;
use App\Repository\GameTextRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BingoTextController extends AbstractController
{
    public function __construct(
        private BingoTextRepository $bingoTextRepository,
        private GameTextRepository $gameTextRepository,
    ) {
    }

    #[Route(path: '/bingo/{bingo<\d+>}/list', name: 'bingo_text_list', methods: [Request::METHOD_GET])]
    public function list(Bingo $bingo): Response
    {
        $bingoTextForm = $this->createForm(CreateBingoTextType::class, [], ['bingo' => $bingo]);

        return $this->render('bingo_text.html.twig', [
            'create_bingo_text' => $bingoTextForm,
            'texts' => $this->bingoTextRepository->findBy(['bingo' => $bingo]),
        ]);
    }

    #[Route(path: '/bingo/{bingo<\d+>}/create', name: 'bingo_text_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, Bingo $bingo): Response
    {
        $bingoTextForm = $this->createForm(CreateBingoTextType::class, [], ['bingo' => $bingo]);
        $bingoTextForm->handleRequest($request);

        if ($bingoTextForm->isSubmitted()) {
            if ($bingoTextForm->isValid()) {
                $this->bingoTextRepository->create([
                    'bingo' => $bingo,
                    'text' => $bingoTextForm->getData()['text'],
                ]);
                $this->bingoTextRepository->save();
            } else {
                $errors = $bingoTextForm->getErrors();

                while ($errors->hasChildren()) {
                    $this->addFlash('error', $errors->current()->getMessage());
                    $errors->next();
                }
            }
        }

        return $this->redirectToRoute('bingo_text_list', ['bingo' => $bingo->getId()]);
    }

    #[Route(path: '/bingo/{bingo<\d+>}/delete', name: 'bingo_text_delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request, Bingo $bingo): Response
    {
        $bingoText = $this->bingoTextRepository->find($request->request->getInt('bingo_text_id'));

        if ($bingoText) {
            if ($gameTexts = $this->gameTextRepository->findBy(['bingoText' => $bingoText])) {
                foreach ($gameTexts as $gameText) {
                    $this->addFlash('error', sprintf("Text is in use in %s's bingo", $gameText->getGame()->getUser()->getUsername()));
                }
                return $this->redirectToRoute('bingo_text_list', ['bingo' => $bingo->getId()]);
            }

            $this->bingoTextRepository->delete($bingoText);
            $this->bingoTextRepository->save();
        }

        return $this->redirectToRoute('bingo_text_list', ['bingo' => $bingo->getId()]);
    }

    #[Route(path: '/bingo/{bingo<\d+>}/update', name: 'bingo_text_update', methods: [Request::METHOD_POST])]
    public function update(Request $request, Bingo $bingo): Response
    {
        $bingoText = $this->bingoTextRepository->find($request->request->getInt('bingo_text_id'));

        if ($bingoText) {
            $bingoText->setText($request->request->get('text'));
            $this->bingoTextRepository->save();
        }

        return $this->redirectToRoute('bingo_text_list', ['bingo' => $bingo->getId()]);
    }
}