<?php

namespace App\Entity;

use App\Repository\GameTextRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameTextRepository::class)]
class GameText
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column
    ]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['remove'], inversedBy: 'gameTexts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'gameTexts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BingoText $bingoText = null;

    #[ORM\Column]
    private bool $active = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBingoText(): ?BingoText
    {
        return $this->bingoText;
    }

    public function setBingoText(?BingoText $bingoText): self
    {
        $this->bingoText = $bingoText;
        return $this;
    }
    
    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }
}
