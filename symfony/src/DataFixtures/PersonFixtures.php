<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $csv = fopen(dirname(__FILE__).'/resources/data/personen.csv', 'r');

        $i = 0;

        while (!feof($csv)) {
            $line = fgetcsv($csv);
            /** var $coordinatesfrcity[$i] = Person **/
            $person = new Person();
            $person->setFirstname($line[0]);
            $person->setLastname($line[1]);

            $coordinatesfrcity[$i]->set(;
            $coordinatesfrcity[$i]->setAreaPost2016($line[1]);
            $coordinatesfrcity[$i]->setDeptNum($line[2]);
            $coordinatesfrcity[$i]->setDeptName($line[3]);
            $coordinatesfrcity[$i]->setdistrict($line[4]);
            $coordinatesfrcity[$i]->setpostCode($line[5]);
            $coordinatesfrcity[$i]->setCity($line[6]);

            $manager->persist($coordinatesfrcity[$i]);

            $this->addReference('coordinatesfrcity-'.$i, $coordinatesfrcity[$i]);


            $i = $i + 1;
        }

        fclose($csv);
    }
}
