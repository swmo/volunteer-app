<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Enrollment;
use App\Entity\Organisation;
use App\Entity\Person;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404082055 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

    public function postUp(Schema $schema) : void
    {
        /** @var EntityManagerInterface $em  */
        $em = $this->container->get('doctrine.orm.entity_manager');

        /** @var Organisation $organisation */
        $organisation = $em->getRepository(Organisation::class)->findOneBy(['id' => 2]);
        $persons = $em->getRepository(Person::class)->findAll();
        foreach($persons as $person){
            /** @var Person $person  */
            $person->addOrganisation($organisation);
            $em->persist($person);
            $em->flush();
        }
    }
}
