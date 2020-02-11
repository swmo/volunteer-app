<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserOrganisationRepository")
 */
class UserOrganisation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userOrganisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $appuser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisation", inversedBy="userOrganisations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppuser(): ?User
    {
        return $this->appuser;
    }

    public function setAppuser(?User $appuser): self
    {
        $this->appuser = $appuser;

        return $this;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation(?Organisation $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }
}
