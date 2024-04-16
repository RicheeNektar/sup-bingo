<?php

namespace App\Entity;

use App\Repository\BingoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BingoRepository::class)]
class Bingo
{
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column,
    ]
    private int $id;

    #[ORM\Column(type: 'string', length: 64, nullable: false)]
    private string $name;

    /**
     * @var Collection<int, BingoText>
     */
    #[ORM\OneToMany(targetEntity: BingoText::class, mappedBy: 'bingo', cascade: ['remove'])]
    private Collection $bingoTexts;

    public function __construct()
    {
        $this->bingoTexts = new ArrayCollection();
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, BingoText>
     */
    public function getBingoTexts(): Collection
    {
        return $this->bingoTexts;
    }

    public function addBingoText(BingoText $bingoText): self
    {
        if (!$this->bingoTexts->contains($bingoText)) {
            $this->bingoTexts->add($bingoText);
            $bingoText->setBingo($this);
        }

        return $this;
    }

    public function removeBingoText(BingoText $bingoText): self
    {
        if ($this->bingoTexts->removeElement($bingoText)) {
            // set the owning side to null (unless already changed)
            if ($bingoText->getBingo() === $this) {
                $bingoText->setBingo(null);
            }
        }

        return $this;
    }
}