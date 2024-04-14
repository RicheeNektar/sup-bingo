<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column
    ]
    private ?int $id = null;

    /**
     * @var Collection<int, GameText>
     */
    #[ORM\OneToMany(targetEntity: GameText::class, mappedBy: 'game', cascade: ['remove'])]
    private Collection $gameTexts;

    #[ORM\OneToOne(mappedBy: 'game')]
    private ?User $user = null;

    public function __construct()
    {
        $this->gameTexts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, GameText>
     */
    public function getGameTexts(): Collection
    {
        return $this->gameTexts;
    }

    public function addGameText(GameText $gameText): static
    {
        if (!$this->gameTexts->contains($gameText)) {
            $this->gameTexts->add($gameText);
            $gameText->setGame($this);
        }

        return $this;
    }

    public function removeGameText(GameText $gameText): static
    {
        if ($this->gameTexts->removeElement($gameText)) {
            // set the owning side to null (unless already changed)
            if ($gameText->getGame() === $this) {
                $gameText->setGame(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setGame(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getGame() !== $this) {
            $user->setGame($this);
        }

        $this->user = $user;
        return $this;
    }
}
