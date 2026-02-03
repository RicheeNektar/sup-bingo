<?php

namespace App\Entity;

use App\Entity\User\Role;
use App\Repository\UserRepository;
use App\Type\UserRoleType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[
        ORM\Id,
        ORM\GeneratedValue(strategy: 'CUSTOM'),
        ORM\CustomIdGenerator(UlidGenerator::class),
        ORM\Column(type: UlidType::NAME),
    ]
    public Ulid $ulid;

    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: false)]
    public string $username;

    #[ORM\Column(type: 'string', nullable: false)]
    public string $password;

    #[ORM\Column(type: UserRoleType::NAME, nullable: false)]
    public Role $role = Role::User;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Game $game = null;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function __serialize(): array
    {
        return [
            'ulid' => (string) $this->ulid,
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'role' => $this->role->value,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->ulid = Ulid::fromString($data['ulid']);
        $this->setUsername($data['username']);
        $this->setPassword($data['password']);
        $this->setRole(Role::from($data['role']));
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;
        return $this;
    }
}