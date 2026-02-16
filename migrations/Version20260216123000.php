<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260216123000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace legacy json_array type comment with json for enrollment.status';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('COMMENT ON COLUMN enrollment.status IS \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('COMMENT ON COLUMN enrollment.status IS \'(DC2Type:json_array)\'');
    }
}
