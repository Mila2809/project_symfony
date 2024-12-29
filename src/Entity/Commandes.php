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
    #[ORM\OneToMany(targetEntity: ContenuPanier::class, mappedBy: 'commandes')]
    private Collection $ContenuPanier;

    public function __construct()
    {
        $this->ContenuPanier = new ArrayCollection();
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
    public function getContenuPanier(): Collection
    {
        return $this->ContenuPanier;
    }

    public function addContenuPanier(ContenuPanier $contenuPanier): static
    {
        if (!$this->ContenuPanier->contains($contenuPanier)) {
            $this->ContenuPanier->add($contenuPanier);
            $contenuPanier->setCommandes($this);
        }

        return $this;
    }

    public function removeContenuPanier(ContenuPanier $contenuPanier): static
    {
        if ($this->ContenuPanier->removeElement($contenuPanier)) {
            // set the owning side to null (unless already changed)
            if ($contenuPanier->getCommandes() === $this) {
                $contenuPanier->setCommandes(null);
            }
        }

        return $this;
    }
}
