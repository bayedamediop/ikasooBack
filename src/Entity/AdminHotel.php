<?php

namespace App\Entity;

use App\Repository\AdminHotelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdminHotelRepository::class)
 */
class AdminHotel extends User
{

   
}
