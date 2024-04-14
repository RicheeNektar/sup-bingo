<?php

namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

final class UniqueUsername extends Constraint
{
    public string $message = 'A user with the username "{{ username }}" already exists';

    public function validatedBy(): string
    {
        return UniqueUsernameValidator::class;
    }
}