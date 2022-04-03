<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210194752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE appuser ADD selected_organisation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appuser ADD CONSTRAINT FK_EE8A7C74945BDC54 FOREIGN KEY (selected_organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EE8A7C74945BDC54 ON appuser (selected_organisation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE appuser DROP CONSTRAINT FK_EE8A7C74945BDC54');
        $this->addSql('DROP INDEX IDX_EE8A7C74945BDC54');
        $this->addSql('ALTER TABLE appuser DROP selected_organisation_id');
    }
}
