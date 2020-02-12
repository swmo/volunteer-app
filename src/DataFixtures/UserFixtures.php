<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('personal@burgdorfer-stadtlauf.ch');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'demo'
        ));
        $this->addReference('User_Stadtlauf_Burgdorf', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('admin@local');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'demo'
        ));
        $this->addReference('User_Admin', $user);
        $manager->persist($user);


        $user = new User();
        $user->setEmail('personal@tennisverein-burgdorf.ch');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'demo'
        ));
        $manager->persist($user);
        $this->addReference('User_Tennisverein_Burgdorf', $user);

       $manager->flush();
    }

}
