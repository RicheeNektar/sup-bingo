<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('game');
        }

        $loginForm = $this->createForm(LoginType::class);

        return $this->render('login.html.twig', [
            'login_form' => $loginForm,
            'last_username' => $authenticationUtils->getLastUsername(),
            'last_error' => $authenticationUtils->getLastAuthenticationError()?->getMessageKey(),
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }
}