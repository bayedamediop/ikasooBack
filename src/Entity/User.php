<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({ "user" = "User",
 *     "adminSysteme"="AdminSysteme",
 *     "adminAgence"="AdminAgence",
 *     "adminHotel"="AdminHotel",
 *     "utilisateur"="Utilisateur"
 *     })
 * @ApiResource(
 *     collectionOperations={
 *          "add_user"={
 *              "route_name"="addUser",
 *          },
 *     "get_users"={
 *                  "method"="GET",
 *                    "path" = "/admin/user",
 *                     "normalization_context"={"groups"={"usersRead:read"}},
 *              },
 *
 *      },
 *      itemOperations={
 *
 *     "archive_user"={
 *                  "route_name"="archiveUser",
 *              },
 *      "put_user"={
 *                 "route_name"="putUser",
 *              },
 *
 *   "get_user_by_id"={
 *                  "method"="GET",
 *                    "path" = "/user/{id}",
 *                     "normalization_context"={"groups"={"usersRead:read"}},
 *              },
 *
 *      },
 *
 * )
 *  @ApiFilter(BooleanFilter::class, properties={"archivage"})
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups ({"usersRead:read","getReservationdunUser"})
     */
    private $email;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups ({"usersRead:read"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"usersRead:read","getReservationdunUser"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"usersRead:read","getReservationdunUser"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"usersRead:read"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"usersRead:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"usersRead:read"})
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $abonnement = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archivage =true;

    /**
     * @ORM\ManyToOne(targetEntity=Profils::class, inversedBy="users")
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="user")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Reservations::class, mappedBy="user")
     */
    private $reservations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"usersRead:read"})
     */
    private $nomEntreprise;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAvatar()
    {
        $avatar = $this->avatar;
        if ($avatar) {
            return (base64_encode(stream_get_contents($this->avatar)));
        }
        return $avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAbonnement(): ?bool
    {
        return $this->abonnement;
    }

    public function setAbonnement(bool $abonnement): self
    {
        $this->abonnement = $abonnement;

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

    public function getProfil(): ?Profils
    {
        return $this->profil;
    }

    public function setProfil(?Profils $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

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
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(?string $nomEntreprise): self
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }
}
