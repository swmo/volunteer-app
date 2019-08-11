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
    private $differentStartTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $differentEndTime;


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

    public function getWorkingTime()
    {
        
       

        if($this->getMissionChoice01()){
            $interval1 = $this->getMissionChoice01()->getStart();
            $interval1 = $interval1->diff($this->getMissionChoice01()->getEnd());
        }
        else{
            $interval1 = new \DateTime('00:00');
            $interval1 = $interval1->diff(new \DateTime('00:00'));
        }

        if($this->getMissionChoice02()){
            $interval2 = $this->getMissionChoice02()->getStart();
            $interval2 = $interval2->diff($this->getMissionChoice02()->getEnd());
        }
        else {
            $interval2 = new \DateTime('00:00');
            $interval2 = $interval2->diff(new \DateTime('00:00'));
        }

        $e = new \DateTime('00:00');
        $f = clone $e;
        $e->add($interval1);
        $e->add($interval2);

        return $f->diff($e);
    }

    public function getDifferentStartTime(): ?\DateTimeInterface
    {
        return $this->differentStartTime;
    }

    public function setDifferentStartTime(?\DateTimeInterface $differentStartTime): self
    {
        $this->differentStartTime = $differentStartTime;

        return $this;
    }

    public function getDifferentEndTime(): ?\DateTimeInterface
    {
        return $this->differentEndTime;
    }

    public function setDifferentEndTime(?\DateTimeInterface $differentEndTime): self
    {
        $this->differentEndTime = $differentEndTime;

        return $this;
    }

    public function getStart(){

    }

    public function getEnd(){
        
    }

}
