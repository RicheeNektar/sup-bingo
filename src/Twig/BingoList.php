<?php

namespace App\Twig;

use App\Repository\BingoRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class BingoList extends AbstractExtension
{
    public function __construct(
        private readonly BingoRepository $bingoRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bingo_list', [$this, '__invoke'])
        ];
    }

    public function __invoke(): array
    {
        return array_filter(
            $this->bingoRepository->findAll(),
            fn ($bingo) => count($bingo->getBingoTexts()) >= 25
        );
    }
}