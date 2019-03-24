<?php

namespace App\DataFixtures;


use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $file = dirname(__FILE__).'/../../resources/data/personen.csv';
        if (($handle = fopen($file, 'r')) !== FALSE) {

            $i = 0;

            while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

                $person = new Person();
                $person->setFirstname($data[0]);
                $person->setLastname($data[1]);
                $person->setStreet($data[2]);
                $person->setZip($data[3]);
                $person->setMobile($data[5]);
                $person->setEmail($data[6]);
                $person->setCity($data[4]);
                $person->setRemark($data[7]);

                $manager->persist($person);

                $this->addReference('person-'.$i, $person);
                $i++;

            }
            fclose($handle);
        }

        $manager->flush();
    }
}
