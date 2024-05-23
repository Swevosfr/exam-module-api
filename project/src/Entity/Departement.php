<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ["groups" =>["departement:read:collection"]]
        ),
        new Get(
            normalizationContext: ["groups"=>["departement:read"]]
        ),
        new Post(
            normalizationContext: ["groups"=>["departement:read"]],
            denormalizationContext: ["groups"=>["departement:write"]]
        ),
        new Patch(
            normalizationContext: ["groups"=>["departement:read"]],
            denormalizationContext: ["groups"=>["departement:write"]]
        ),
        new Delete(
            normalizationContext: ["groups"=>["departement:delete"]]
            //security: "is_granted('ROLE_USER') and object.getOwner()
        )
    ]
)]

#[ApiFilter(SearchFilter::class, properties: [
    "region"=>"partial",
    "label"=>"partial",
    "numero"=>"exact"
])]

#[ApiFilter(OrderFilter::class, properties: [
    "label",
    "numero"
])]

class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    #[Groups(["departement:read:collection", "departement:read"])]
    private ?string $numero = null;

    #[ORM\Column(length: 100)]
    #[Groups(["departement:read:collection", "departement:read"])]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Groups(["departement:read:collection", "departement:read"])]
    private ?string $region = null;

    #[ORM\OneToMany(targetEntity: Mairie::class, mappedBy: 'departement', orphanRemoval: true)]
    private Collection $mairies;

    public function __construct()
    {
        $this->mairies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return Collection<int, Mairie>
     */
    public function getMairies(): Collection
    {
        return $this->mairies;
    }

    public function addMairy(Mairie $mairy): static
    {
        if (!$this->mairies->contains($mairy)) {
            $this->mairies->add($mairy);
            $mairy->setDepartement($this);
        }

        return $this;
    }

    public function removeMairy(Mairie $mairy): static
    {
        if ($this->mairies->removeElement($mairy)) {
            // set the owning side to null (unless already changed)
            if ($mairy->getDepartement() === $this) {
                $mairy->setDepartement(null);
            }
        }

        return $this;
    }
}
