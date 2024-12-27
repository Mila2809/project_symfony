<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Utilisateur $Utilisateur = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DateAchat = null;

    /**
     * @var Collection<int, ContenuPanier>
     */
    #[ORM\ManyToMany(targetEntity: ContenuPanier::class, inversedBy: 'commandes')]
    private Collection $ContenuPaniers;

    public function __construct()
    {
        $this->ContenuPaniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?Utilisateur $Utilisateur): static
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->DateAchat;
    }

    public function setDateAchat(\DateTimeInterface $DateAchat): static
    {
        $this->DateAchat = $DateAchat;

        return $this;
    }

    /**
     * @return Collection<int, ContenuPanier>
     */
    public function getContenuPaniers(): Collection
    {
        return $this->ContenuPaniers;
    }

    public function addContenuPanier(ContenuPanier $ContenuPanier): static
    {
        if (!$this->ContenuPaniers->contains($ContenuPanier)) {
            $this->ContenuPaniers->add($ContenuPanier);
        }

        return $this;
    }

    public function removeContenuPanier(ContenuPanier $ContenuPanier): static
    {
        $this->ContenuPaniers->removeElement($ContenuPanier);

        return $this;
    }
}
