<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnrollmentRepository")
 */
class Enrollment
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
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $zip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mobile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mission")
     */
    private $missionChoice01;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Mission")
     */
    private $missionChoice02;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tshirtsize;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasTshirt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confirmToken;


    /** 
     * @ORM\Column(type="json_array", nullable=true) 
     * */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $organizedStartTimeMissonChoice01;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $organizedEndTimeMissonChoice01;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $organizedDescriptionMissonChoice01;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $organizedStartTimeMissonChoice02;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $organizedEndTimeMissonChoice02;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $organizedDescriptionMissonChoice02;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
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

    public function getMissionChoice01(): ?mission
    {
        return $this->missionChoice01;
    }

    public function setMissionChoice01(?mission $missionChoice01): self
    {
        $this->missionChoice01 = $missionChoice01;

        return $this;
    }

    public function getMissionChoice02(): ?Mission
    {
        return $this->missionChoice02;
    }

    public function setMissionChoice02(?Mission $missionChoice02): self
    {
        $this->missionChoice02 = $missionChoice02;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getTshirtsize(): ?string
    {
        return $this->tshirtsize;
    }

    public function setTshirtsize(string $tshirtsize): self
    {
        $this->tshirtsize = $tshirtsize;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getHasTshirt(): ?bool
    {
        return $this->hasTshirt;
    }

    public function setHasTshirt(bool $hasTshirt): self
    {
        $this->hasTshirt = $hasTshirt;
        return $this;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function setConfirmToken(?string $confirmToken): self
    {
        $this->confirmToken = $confirmToken;
        return $this;
    }

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(?array $status): self
    {
        $this->status = $status;
        return $this;
    }


    public function getWorkingTime(){

        $e = new \DateTime('00:00');
        $f = clone $e;
        $e->add($this->getWorkingTimeMissionChoice01());
        $e->add($this->getWorkingTimeMissionChoice02());
        return $f->diff($e);
    }
    public function getWorkingTimeMissionChoice01()
    {
        
        if($this->getMissionChoice01()){

            if($this->getOrganizedStartTimeMissionChoice01())
            {
                $interval1 = $this->getOrganizedStartTimeMissionChoice01();
            }
            else {
                $interval1 = $this->getMissionChoice01()->getStart();
            }
           
            if($this->getOrganizedEndTimeMissionChoice01()){
                $interval1 = $interval1->diff($this->getOrganizedEndTimeMissionChoice01());
            }
            else {
                $interval1 = $interval1->diff($this->getMissionChoice01()->getEnd());
            }
        
        }
        else{
            $interval1 = new \DateTime('00:00');
            $interval1 = $interval1->diff(new \DateTime('00:00'));
        }


        $e = new \DateTime('00:00');
        $f = clone $e;
        $e->add($interval1);
        return $f->diff($e);
    }

    public function getWorkingTimeMissionChoice02()
    {        
        if($this->getMissionChoice02()){

            if($this->getOrganizedStartTimeMissionChoice02())
            {
                $interval1 = $this->getOrganizedStartTimeMissionChoice02();
            }
            else {
                $interval1 = $this->getMissionChoice02()->getStart();
            }
        
            if($this->getOrganizedEndTimeMissionChoice02()){
                $interval1 = $interval1->diff($this->getOrganizedEndTimeMissionChoice02());
            }
            else {
                $interval1 = $interval1->diff($this->getMissionChoice02()->getEnd());
            }
            
        }
        else{
            $interval1 = new \DateTime('00:00');
            $interval1 = $interval1->diff(new \DateTime('00:00'));
        }

        $e = new \DateTime('00:00');
        $f = clone $e;
        $e->add($interval1);
        return $f->diff($e);
        
    }


    //MISSON 01
    public function getOrganizedStartTimeMissionChoice01(): ?\DateTimeInterface
    {
        return $this->organizedStartTimeMissonChoice01;
    }

    public function setOrganizedStartTimeMissionChoice01(?\DateTimeInterface $organizedStartTimeMissonChoice01): self
    {
        $this->organizedStartTimeMissonChoice01 = $organizedStartTimeMissonChoice01;

        return $this;
    }

    public function getOrganizedEndTimeMissionChoice01(): ?\DateTimeInterface
    {
        return $this->organizedEndTimeMissonChoice01;
    }

    public function setOrganizedEndTimeMissionChoice01(?\DateTimeInterface $organizedEndTimeMissionChoice01): self
    {
        $this->organizedEndTimeMissionChoice01 = $organizedEndTimeMissionChoice01;

        return $this;
    }

    public function getOrganizedDescriptionMissionChoice01(): ?string
    {
        return $this->organizedDescriptionMissionChoice01;
    }

    public function setOrganizedDescriptionMissionChoice01(?string $organizedDescriptionMissonChoice01): self
    {
        $this->organizedDescriptionMissonChoice01 = $organizedDescriptionMissonChoice01;
        return $this;
    }


    //MISSON 02
    public function getOrganizedStartTimeMissionChoice02(): ?\DateTimeInterface
    {
        return $this->organizedStartTimeMissonChoice02;
    }

    public function setOrganizedStartTimeMissionChoice02(?\DateTimeInterface $organizedStartTimeMissonChoice02): self
    {
        $this->organizedStartTimeMissonChoice02 = $organizedStartTimeMissonChoice02;

        return $this;
    }

    public function getOrganizedEndTimeMissionChoice02(): ?\DateTimeInterface
    {
        return $this->organizedEndTimeMissonChoice02;
    }

    public function setOrganizedEndTimeMissionChoice02(?\DateTimeInterface $organizedEndTimeMissionChoice02): self
    {
        $this->organizedEndTimeMissionChoice02 = $organizedEndTimeMissionChoice02;

        return $this;
    }

    public function getOrganizedDescriptionMissionChoice02(): ?string
    {
        return $this->organizedDescriptionMissionChoice02;
    }

    public function setOrganizedDescriptionMissionChoice02(?string $organizedDescriptionMissonChoice02): self
    {
        $this->organizedDescriptionMissonChoice02 = $organizedDescriptionMissonChoice02;
        return $this;
    }
}
