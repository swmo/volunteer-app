<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225121300 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE enrollment ADD project_id INT NULL');
        
        //because one project already exists on prod:
        $this->addSql('UPDATE enrollment SET project_id=1');
        $this->addSql('ALTER TABLE enrollment ALTER project_id SET NOT NULL');

        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DBDCD7E1166D1F9C ON enrollment (project_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE enrollment DROP CONSTRAINT FK_DBDCD7E1166D1F9C');
        $this->addSql('DROP INDEX IDX_DBDCD7E1166D1F9C');
        $this->addSql('ALTER TABLE enrollment DROP project_id');
    }
}
