<?php

namespace App\Constraint;

use App\Entity\Bingo;
use Symfony\Component\Validator\Constraint;

final class UniqueBingoText extends Constraint
{
    public string $message = 'The entered text exists already.';

    public Bingo $bingo;

    public function getRequiredOptions(): array
    {
        return [
            'bingo'
        ];
    }

    public function validatedBy(): string
    {
        return UniqueBingoTextValidator::class;
    }
}