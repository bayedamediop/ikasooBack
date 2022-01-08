<?php

namespace App\Entity;

use App\Repository\AdminAgenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdminAgenceRepository::class)
 */
class AdminAgence extends User
{

}
