<?php

namespace App\Type;

use App\Entity\User\Role;

final class UserRoleType extends AbstractEnumType
{
    protected string $enumClass = Role::class;
    public const NAME = 'user_role';
}