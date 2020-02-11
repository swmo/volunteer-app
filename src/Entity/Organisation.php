<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganisationRepository")
 */
class Organisation
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Person", mappedBy="organisations")
     */
    private $people;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project", mappedBy="organisation")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserOrganisation", mappedBy="organisation", orphanRemoval=true)
     */
    private $userOrganisations;

    public function __construct()
    {
        $this->people = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->userOrganisations = new ArrayCollection();
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

    /**
     * @return Collection|Person[]
     */
    public function getPeople(): Collection
    {
        return $this->people;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->people->contains($person)) {
            $this->people[] = $person;
            $person->addOrganisation($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->people->contains($person)) {
            $this->people->removeElement($person);
            $person->removeOrganisation($this);
        }

        return $this;
    }

    /**
     * @return Collection|Project[]
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->setOrganisation($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            // set the owning side to null (unless already changed)
            if ($project->getOrganisation() === $this) {
                $project->setOrganisation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserOrganisation[]
     */
    public function getUserOrganisations(): Collection
    {
        return $this->userOrganisations;
    }

    public function addUserOrganisation(UserOrganisation $userOrganisation): self
    {
        if (!$this->userOrganisations->contains($userOrganisation)) {
            $this->userOrganisations[] = $userOrganisation;
            $userOrganisation->setOrganisation($this);
        }

        return $this;
    }

    public function removeUserOrganisation(UserOrganisation $userOrganisation): self
    {
        if ($this->userOrganisations->contains($userOrganisation)) {
            $this->userOrganisations->removeElement($userOrganisation);
            // set the owning side to null (unless already changed)
            if ($userOrganisation->getOrganisation() === $this) {
                $userOrganisation->setOrganisation(null);
            }
        }

        return $this;
    }
}
