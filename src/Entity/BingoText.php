<?php

namespace App\Entity;

use App\Repository\BingoTextRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BingoTextRepository::class)]
class BingoText
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column
    ]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bingoTexts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Bingo $bingo = null;

    #[ORM\Column(length: 255, nullable: false)]
    private string $text;

    /**
     * @var Collection<int, GameText>
     */
    #[ORM\OneToMany(targetEntity: GameText::class, mappedBy: 'bingoText')]
    private Collection $gameTexts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBingo(): ?Bingo
    {
        return $this->bingo;
    }

    public function setBingo(?Bingo $bingo): self
    {
        $this->bingo = $bingo;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
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
            $gameText->setBingoText($this);
        }

        return $this;
    }

    public function removeGameText(GameText $gameText): static
    {
        if ($this->gameTexts->removeElement($gameText)) {
            if ($gameText->getBingoText() === $this) {
                $gameText->setBingoText(null);
            }
        }

        return $this;
    }
}
