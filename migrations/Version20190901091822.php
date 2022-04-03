<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190901091822 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice01 TYPE TEXT');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice01 DROP DEFAULT');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice02 TYPE TEXT');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice02 DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice01 TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice01 DROP DEFAULT');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice02 TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE enrollment ALTER organized_description_mission_choice02 DROP DEFAULT');
    }
}
