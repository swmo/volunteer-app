<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Gedmo\Loggable
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="appuser")
 * need to set to appuse instead of user because of a reserved word of postgres
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisation")
     */
    private $selectedOrganisation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserOrganisation", mappedBy="appuser", orphanRemoval=true)
     */
    private $userOrganisations;

    /*
    * Notwendig um das Klartext Password temporär zu speichern -> ist kein Feld in der DB
    */
    private $plainPassword;



    public function __construct()
    {
        $this->userOrganisations = new ArrayCollection();
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /*
    * To set a new Password use setPlainPassword Method.
    * in setPassword you have to use the already encryptet password.
    */ 
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        /*
         * Die nullung des plainPassword wird benötit damit garantiert
         * werden kann das der Listener für das Hashing des Passwortes
         * aufgerufen wird auch wenn nur das Password zurückgesetzt wird.
         */
        $this->password = null;
        return $this;
    }

    public function getPlainPassword(): ?string 
    {
        return $this->plainPassword;
    }
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getSelectedOrganisation(): ?Organisation
    {
        return $this->selectedOrganisation;
    }

    public function setSelectedOrganisation(?Organisation $selectedOrganisation): self
    {
        $this->selectedOrganisation = $selectedOrganisation;

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
            $userOrganisation->setAppuser($this);
        }

        return $this;
    }

    public function removeUserOrganisation(UserOrganisation $userOrganisation): self
    {
        if ($this->userOrganisations->contains($userOrganisation)) {
            $this->userOrganisations->removeElement($userOrganisation);
            // set the owning side to null (unless already changed)
            if ($userOrganisation->getAppuser() === $this) {
                $userOrganisation->setAppuser(null);
            }
        }

        return $this;
    }
}
