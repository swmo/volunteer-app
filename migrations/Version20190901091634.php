<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190901091634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE enrollment ADD organized_start_time_mission_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_end_time_mission_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_description_mission_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_start_time_mission_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_end_time_mission_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_description_mission_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment DROP organized_start_time_misson_choice01');
        $this->addSql('ALTER TABLE enrollment DROP organized_end_time_misson_choice01');
        $this->addSql('ALTER TABLE enrollment DROP organized_description_misson_choice01');
        $this->addSql('ALTER TABLE enrollment DROP organized_start_time_misson_choice02');
        $this->addSql('ALTER TABLE enrollment DROP organized_end_time_misson_choice02');
        $this->addSql('ALTER TABLE enrollment DROP organized_description_misson_choice02');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE enrollment ADD organized_start_time_misson_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_end_time_misson_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_description_misson_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_start_time_misson_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_end_time_misson_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment ADD organized_description_misson_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE enrollment DROP organized_start_time_mission_choice01');
        $this->addSql('ALTER TABLE enrollment DROP organized_end_time_mission_choice01');
        $this->addSql('ALTER TABLE enrollment DROP organized_description_mission_choice01');
        $this->addSql('ALTER TABLE enrollment DROP organized_start_time_mission_choice02');
        $this->addSql('ALTER TABLE enrollment DROP organized_end_time_mission_choice02');
        $this->addSql('ALTER TABLE enrollment DROP organized_description_mission_choice02');
    }
}
