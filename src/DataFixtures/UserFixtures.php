<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('personal@burgdorfer-stadtlauf.ch');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPlainPassword('demo');
        $this->addReference('User_Stadtlauf_Burgdorf', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('admin@local');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPlainPassword('demo');
        $this->addReference('User_Admin', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('personal@tennisverein-burgdorf.ch');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPlainPassword('demo');
        $manager->persist($user);
        $this->addReference('User_Tennisverein_Burgdorf', $user);

       $manager->flush();
    }

}
