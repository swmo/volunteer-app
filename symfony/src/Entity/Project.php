<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisation", inversedBy="projects")
     */
    private $organisation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mission", mappedBy="Project")
     */
    private $missions;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : null},nullable=true)
     * Domain auf welche das Projekt geladen werden soll
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255, options={"default" : null},nullable=true)
     * Schlüssel welcher im DNS hinterlegt werden muss damit die zuordnung gemacht werden kann.
     */
    private $domainProofKey;

    /**
     * @ORM\Column(type="boolean",options={"default" : false})
     */
    private $isEnabled;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $name): self
    {
        $this->domain = $name;

        return $this;
    }

    public function getDomainProofKey(): ?string
    {
        return $this->domainProofKey;
    }

    public function setDomainProofKey(string $key): self
    {
        $this->domainProofKey = $key;
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

    /**
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setProject($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->contains($mission)) {
            $this->missions->removeElement($mission);
            // set the owning side to null (unless already changed)
            if ($mission->getProject() === $this) {
                $mission->setProject(null);
            }
        }

        return $this;
    }
    
    public function isEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function __toString()
    {
        return $this->getOrganisation()->getName() . ' ' . $this->getName();
    }
}
