<?php

namespace App\Entity\User;

enum Role: string
{
    case Admin = 'ROLE_ADMIN';
    case User = 'ROLE_USER';
}
