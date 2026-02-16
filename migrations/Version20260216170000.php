<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260216170000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Store mission images in database (blob + mime type).';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE mission ADD image_data BYTEA DEFAULT NULL');
        $this->addSql('ALTER TABLE mission ADD image_mime_type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('ALTER TABLE mission DROP image_data');
        $this->addSql('ALTER TABLE mission DROP image_mime_type');
    }
}
