<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404082055 extends AbstractMigration
{
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
        $this->connection->executeStatement(
            <<<'SQL'
            INSERT INTO person_organisation (person_id, organisation_id)
            SELECT p.id, 2
            FROM person p
            WHERE EXISTS (SELECT 1 FROM organisation o WHERE o.id = 2)
              AND NOT EXISTS (
                SELECT 1
                FROM person_organisation po
                WHERE po.person_id = p.id
                  AND po.organisation_id = 2
              )
            SQL
        );
    }
}
