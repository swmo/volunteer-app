<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260218223000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create communication_template table for DB-backed email templates';
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform()->getName();

        if ('postgresql' === $platform) {
            $this->addSql('CREATE TABLE communication_template (id SERIAL NOT NULL, template_key VARCHAR(120) NOT NULL, subject VARCHAR(255) NOT NULL, body TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        } elseif ('sqlite' === $platform) {
            $this->addSql('CREATE TABLE communication_template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, template_key VARCHAR(120) NOT NULL, subject VARCHAR(255) NOT NULL, body CLOB NOT NULL, updated_at DATETIME DEFAULT NULL)');
        } else {
            $this->addSql('CREATE TABLE communication_template (id INT AUTO_INCREMENT NOT NULL, template_key VARCHAR(120) NOT NULL, subject VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX uniq_communication_template_key (template_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if ('postgresql' === $platform || 'sqlite' === $platform) {
            $this->addSql('CREATE UNIQUE INDEX uniq_communication_template_key ON communication_template (template_key)');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE communication_template');
    }
}

