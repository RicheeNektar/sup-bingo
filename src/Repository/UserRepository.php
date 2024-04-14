<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserRepository extends AbstractRepository
{
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
    ) {
        parent::__construct($entityManager, $registry, User::class);
    }

    public function create(mixed $data): User
    {
        ($user = new User())
            ->setUsername($data['username'])
            ->setPassword($this->userPasswordHasher->hashPassword($user, $data['password']));

        $this->getEntityManager()->persist($user);

        return $user;
    }
}