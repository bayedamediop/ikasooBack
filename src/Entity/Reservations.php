<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;

/**
 * @ORM\Entity(repositoryClass=ReservationsRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get_reservationdunUser"={
 *                  "route_name"="getReservationdunUser",
 *              },
 *     "get_reservation_d_un_user"={
 *                  "method"="GET",
 *                    "path" = "/admin/reservations",
 *                     "normalization_context"={"groups"={"reservationRead:read"}},
 *              },
 *
 *      },
 *     itemOperations={
 *
 *     "archive_user"={
 *                  "route_name"="archiveUser",
 *              },
 *      "put_user"={
 *                 "route_name"="putUser",
 *              },
 *
 *   "get_reservation_by_id"={
 *                  "method"="GET",
 *                    "path" = "/reservation/{id}",
 *                     "normalization_context"={"groups"={"reservationRead:read"}},
 *              },
 *
 *      },
 *
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archivage"})
 * @ApiFilter(DateFilter::class, properties={"dateValidation"})
 *
 */
class Reservations
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Articles::class, inversedBy="reservations")
     *@Groups ({"getReservationdunUser","reservationRead:read"})
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="reservations")
     */
    private $client;

    /**
     * @ORM\Column(type="date", nullable=true)
     *  @Groups ({"getReservationdunUser","reservationRead:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     *  @Groups ({"getReservationdunUser","reservationRead:read"})
     */
    private $dateFin;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateAnnulation;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateValidation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Articles
    {
        return $this->article;
    }

    public function setArticle(?Articles $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDateAnnulation(): ?\DateTimeInterface
    {
        return $this->dateAnnulation;
    }

    public function setDateAnnulation(?\DateTimeInterface $dateAnnulation): self
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeInterface $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

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
}
