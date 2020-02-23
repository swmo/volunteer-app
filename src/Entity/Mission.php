<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations


/**
 * @ORM\Entity(repositoryClass="App\Repository\MissionRepository")
 * @Gedmo\Loggable
 */
class Mission 
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
    private $name;

    /**
     * @gedmo\Locale
     * to mark the field as locale used to override global
     * locale settings from TranslatableListener
     * 
     * Load Translation:
     * $entity->setTranslatableLocale($locale);
     * $em->refresh($entity);
     */
    private $locale;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $start;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", name="enddate", nullable=false)
     * end is a reserved word for postgres
     */
    private $end;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="missions")
     */
    private $project;

    /**
     * @ORM\Column(type="integer")
     */
    private $requiredVolunteers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enrollment", mappedBy="missionChoice01")
     */
    private $enrollment01;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enrollment", mappedBy="missionChoice02")
     */
    private $enrollment02;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $meetingPoint;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $calendarEventDescription;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean",options={"default" : true})
     */
    private $isEnabled = true;

    
    public function __construct()
    {
        $this->enrollments = new ArrayCollection();
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

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getNameForSelectbox(){
        return $this->name . ', '.$this->getStart()->format('d.m.Y: H:i').'-' . $this->getEnd()->format('H:i');
    }

    /**
     * Set the value of shortDescription
     *
     * @return  self
     */ 
    public function setShortDescription($shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * Get the value of shortDescription
     */ 
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of end
     */ 
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set the value of end
     *
     * @return  self
     */ 
    public function setEnd(?\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get the value of start
     */ 
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set the value of start
     *
     * @return  self
     */ 
    public function setStart(?\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getRequiredVolunteers(): ?int
    {
        return $this->requiredVolunteers;
    }

    public function setRequiredVolunteers(int $requiredVolunteers): self
    {
        $this->requiredVolunteers = $requiredVolunteers;

        return $this;
    }  
    
    public function countEnrolledVolunteers(): ?int
    {
       return  $this->enrollment01->count() + $this->enrollment02->count();
    }

    public function getMeetingPoint(): ?string
    {
        return $this->meetingPoint;
    }

    public function setMeetingPoint(?string $meetingPoint): self
    {
        $this->meetingPoint = $meetingPoint;

        return $this;
    }

    public function getCalendarEventDescription(): ?string
    {
        return $this->calendarEventDescription;
    }

    public function setCalendarEventDescription(?string $calendarEventDescription): self
    {
        $this->calendarEventDescription = $calendarEventDescription;

        return $this;
    }
    
    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getEnrollments()
    {
        return new ArrayCollection(
            array_merge($this->enrollment01->toArray(), $this->enrollment02->toArray())
        );
    }

}
