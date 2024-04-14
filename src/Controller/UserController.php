<?php

namespace App\Controller;

use App\Entity\User\Role;
use App\Form\RegisterType;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly GameRepository $gameRepository,
    ) {
    }

    #[Route(path: '/user', name: 'user_list', methods: [Request::METHOD_GET])]
    public function list(Request $request): Response
    {
        $registerForm = $this->createForm(RegisterType::class)
            ->handleRequest($request);

        return $this->render(
            'user.html.twig',
            [
                'users' => $this->userRepository->findAll(),
                'roles' => Role::cases(),
                'register_form' => $registerForm,
            ]
        );
    }

    #[Route(path: '/user/create', name: 'user_create', methods: [Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $registerForm = $this->createForm(RegisterType::class)
            ->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $this->userRepository->create($registerForm->getData());
            $this->userRepository->save();
        }

        return $this->redirectToRoute('user_list');
    }

    #[Route(path: '/user/delete', name: 'user_delete', methods: [Request::METHOD_POST])]
    public function delete(Request $request): Response
    {
        $this->userRepository->delete($request->request->get('username'));
        $this->userRepository->save();

        return $this->redirectToRoute('user_list');
    }

    #[Route(path: '/user/role', name: 'user_role', methods: [Request::METHOD_POST])]
    public function changeRole(Request $request): Response
    {
        $user = $this->userRepository->find($request->request->get('username'));

        if ($user) {
            $user->setRole(Role::from($request->request->get('role')));
            $this->userRepository->save();
            return $this->json([]);
        }

        return $this->json([], Response::HTTP_NOT_FOUND);
    }

    #[Route(path: '/user/reset', name: 'user_game_reset', methods: [Request::METHOD_POST])]
    public function resetGame(Request $request): Response
    {
        $user = $this->userRepository->find($request->request->get('username'));

        if ($user) {
            $game = $user->getGame();
            if ($game) {
                $this->gameRepository->delete($game);
                $game->setUser(null);
                $this->userRepository->save();
            }
        }

        return $this->redirectToRoute('user_list');
    }
}