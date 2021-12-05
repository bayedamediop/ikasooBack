<?php

namespace App\Entity;

use App\Repository\AdminSystemeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminSystemeRepository::class)
 */
class AdminSysteme extends User
{


}
