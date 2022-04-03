<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201018132157 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE enrollment ALTER lastname DROP NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER street DROP NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER zip DROP NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER city DROP NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER mobile DROP NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE enrollment ALTER lastname SET NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER street SET NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER zip SET NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER city SET NOT NULL');
        $this->addSql('ALTER TABLE enrollment ALTER mobile SET NOT NULL');
    }
}
