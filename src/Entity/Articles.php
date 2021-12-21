<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *     "get_articles"={
 *                  "method"="GET",
 *                    "path" = "/admin/articles",
 *                     "normalization_context"={"groups"={"articlesRead:read"}},
 *              },
 *
 *      },
 *     )
 * @ApiFilter(BooleanFilter::class, properties={"archivage"})
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"articlesRead:read"})
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"articlesRead:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="blob")
     * @Groups ({"articlesRead:read"})
     */
    private $imageArticle;

    /**
     * @ORM\Column(type="date")
     * @Groups ({"articlesRead:read"})
     */
    private $createAt;

    /**
     * @ORM\Column(type="blob")
     * @Groups ({"articlesRead:read"})
     */
    private $image3D;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"articlesRead:read"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etoile;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="articles")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Reservations::class, mappedBy="article")
     */
    private $reservations;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage = true;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageArticle()
    {
        $imageArticle = $this->imageArticle;
        if ($imageArticle) {
            return (base64_encode(stream_get_contents($this->imageArticle)));
        }
        return $imageArticle;
    }

    public function setImageArticle($imageArticle): self
    {
        $this->imageArticle = $imageArticle;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getImage3D()
    {
        $image3D = $this->image3D;
        if ($image3D) {
            return (base64_encode(stream_get_contents($this->image3D)));
        }
        return $image3D;

    }

    public function setImage3D($image3D): self
    {
        $this->image3D = $image3D;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getEtoile(): ?string
    {
        return $this->etoile;
    }

    public function setEtoile(?string $etoile): self
    {
        $this->etoile = $etoile;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Reservations[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setArticle($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getArticle() === $this) {
                $reservation->setArticle(null);
            }
        }

        return $this;
    }

    public function getArchivage(): ?bool
    {
        return $this->archivage;
    }

    public function setArchivage(bool $archivage): self
    {
        $this->archivage = $archivage;

        return $this;
    }
}
