<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190324142200 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE person_organisation (person_id INT NOT NULL, organisation_id INT NOT NULL, PRIMARY KEY(person_id, organisation_id))');
        $this->addSql('CREATE INDEX IDX_5EFD2F9217BBB47 ON person_organisation (person_id)');
        $this->addSql('CREATE INDEX IDX_5EFD2F99E6B1585 ON person_organisation (organisation_id)');
        $this->addSql('ALTER TABLE person_organisation ADD CONSTRAINT FK_5EFD2F9217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_organisation ADD CONSTRAINT FK_5EFD2F99E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9067F23C166D1F9C ON mission (project_id)');
        $this->addSql('ALTER TABLE project ADD organisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9E6B1585 ON project (organisation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE person_organisation');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C166D1F9C');
        $this->addSql('DROP INDEX IDX_9067F23C166D1F9C');
        $this->addSql('ALTER TABLE mission DROP project_id');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE9E6B1585');
        $this->addSql('DROP INDEX IDX_2FB3D0EE9E6B1585');
        $this->addSql('ALTER TABLE project DROP organisation_id');
    }
}
