<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zip;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $remark;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organisation", inversedBy="people")
     */
    private $organisations;
    

    public function __construct()
    {
        $this->organisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }


    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }


    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(?string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * @return Collection|Organisation[]
     */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    public function addOrganisation(Organisation $organisation): self
    {
        if (!$this->organisations->contains($organisation)) {
            $this->organisations[] = $organisation;
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): self
    {
        if ($this->organisations->contains($organisation)) {
            $this->organisations->removeElement($organisation);
        }

        return $this;
    }
}
