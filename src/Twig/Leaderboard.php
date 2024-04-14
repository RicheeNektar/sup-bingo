<?php

namespace App\Twig;

use App\Repository\UserRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class Leaderboard extends AbstractExtension
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('users', [$this, '__invoke'])
        ];
    }

    public function __invoke(): array
    {
        return $this->userRepository->findBy([], ['points' => 'DESC']);
    }
}