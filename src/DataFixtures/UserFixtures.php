<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('personal@burgdorfer-stadtlauf.ch');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordHasher->hashPassword($user, 'demo'));
        $this->addReference('User_Stadtlauf_Burgdorf', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('admin@local');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordHasher->hashPassword($user, 'demo'));
        $this->addReference('User_Admin', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('personal@tennisverein-burgdorf.ch');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordHasher->hashPassword($user, 'demo'));
        $manager->persist($user);
        $this->addReference('User_Tennisverein_Burgdorf', $user);

       $manager->flush();
    }

}
