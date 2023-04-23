<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $titer = null;

    #[ORM\Column(length: 150)]
    private ?string $info = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 150)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTiter(): ?string
    {
        return $this->titer;
    }

    public function setTiter(string $titer): self
    {
        $this->titer = $titer;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
