<?php

/*
 */

namespace App\Utils;

use App\Entity\Mission;
use App\Entity\Project;
use App\Entity\Enrollment;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

class MergeProjectPerson
{

    private $em = null;
    private $msgs = array();

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }
    
    public function mergeProjectToPerson(Project $project)
    {   
        $organisation = $project->getOrganisation();
        
        $enrollments = $project->getEnrollments();

        foreach($enrollments as $enrollment){
         
            /** @var Person $person */
            $personsFound = $this->em->getRepository(Person::class)->findByOrganisationFirstnameEmail(
                $organisation,
                $enrollment->getFirstname(),
                $enrollment->getEmail()
            );

            if($personsFound) {
                if(count($personsFound) === 1){
                    $person = $personsFound[0];
                }
                else {
                    $this->msgs[] = array('danger',$enrollment->getEmail() . ' ' . $enrollment->getFirstname()  . ' wurde mehrmals gefunden bitte in den Stammdaten bereinigen!');
                    continue;

                } 
            }
            else{
                $person = new Person();
            }

            //setFirstname
            $person->setFirstname($enrollment->getFirstname());
            //setLastname
            $person->setLastname($enrollment->getLastname());
            //setMobile
            $enrollment->getMobile() ? $person->setMobile($enrollment->getMobile()) : null;
            //setStreet
            $enrollment->getStreet() ? $person->setStreet($enrollment->getStreet()) : null;
            //setCity
            $enrollment->getCity() ? $person->setCity($enrollment->getCity()) : null;
            //setZip
            $enrollment->getZip() ? $person->setZip($enrollment->getZip()) : null;
            //setMobile
            $enrollment->getMobile() ? $person->setMobile($enrollment->getMobile()) : null;
            //setEmail
            $enrollment->getEmail() ? $person->setEmail($enrollment->getEmail()) : null;
            
            $this->em->persist($person);
          

        }
        $this->em->flush();
    }

    public function getMsgs(){
        return $this->msgs;
    }

}
