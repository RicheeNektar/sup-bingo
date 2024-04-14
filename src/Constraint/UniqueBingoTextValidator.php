<?php

namespace App\Constraint;

use App\Repository\BingoTextRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueBingoTextValidator extends ConstraintValidator
{
    public function __construct(
        private BingoTextRepository $bingoTextRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueBingoText) {
            throw new UnexpectedTypeException($constraint, UniqueBingoText::class);
        }

        if (
            $this->bingoTextRepository->findBy([
                'bingo' => $constraint->bingo,
                'text' => $value
            ])
        ) {
            $this->context->addViolation($constraint->message);
        }
    }
}