<?php

namespace App\Constraint;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueUsername) {
            throw new UnexpectedTypeException($constraint, UniqueUsername::class);
        }

        if ($this->userRepository->find($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ username }}', $value)
                ->addViolation();
        }
    }
}