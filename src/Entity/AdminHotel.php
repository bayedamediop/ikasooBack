<?php

namespace App\Entity;

use App\Repository\AdminHotelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminHotelRepository::class)
 */
class AdminHotel extends User
{

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomHotel;

    public function getNomHotel(): ?string
    {
        return $this->nomHotel;
    }

    public function setNomHotel(string $nomHotel): self
    {
        $this->nomHotel = $nomHotel;

        return $this;
    }
}
