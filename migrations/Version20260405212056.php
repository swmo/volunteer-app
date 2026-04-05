<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260405212056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE appuser_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE communication_template_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE enrollment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ext_log_entries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mission_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE organisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE person_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_organisation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE appuser (id INT NOT NULL, selected_organisation_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE8A7C74E7927C74 ON appuser (email)');
        $this->addSql('CREATE INDEX IDX_EE8A7C74945BDC54 ON appuser (selected_organisation_id)');
        $this->addSql('CREATE TABLE communication_template (id INT NOT NULL, template_key VARCHAR(120) NOT NULL, subject VARCHAR(255) NOT NULL, body TEXT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_communication_template_key ON communication_template (template_key)');
        $this->addSql('COMMENT ON COLUMN communication_template.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE enrollment (id INT NOT NULL, mission_choice01_id INT DEFAULT NULL, mission_choice02_id INT DEFAULT NULL, project_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, tshirtsize VARCHAR(255) DEFAULT NULL, comment TEXT DEFAULT NULL, has_tshirt BOOLEAN DEFAULT NULL, confirm_token VARCHAR(255) DEFAULT NULL, status JSON DEFAULT NULL, organized_start_time_mission_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, organized_end_time_mission_choice01 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, organized_description_mission_choice01 TEXT DEFAULT NULL, organized_start_time_mission_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, organized_end_time_mission_choice02 TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, organized_description_mission_choice02 TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DBDCD7E1D9B149A4 ON enrollment (mission_choice01_id)');
        $this->addSql('CREATE INDEX IDX_DBDCD7E1CB04E64A ON enrollment (mission_choice02_id)');
        $this->addSql('CREATE INDEX IDX_DBDCD7E1166D1F9C ON enrollment (project_id)');
        $this->addSql('CREATE TABLE ext_log_entries (id INT NOT NULL, action VARCHAR(8) NOT NULL, logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(191) NOT NULL, version INT NOT NULL, data TEXT DEFAULT NULL, username VARCHAR(191) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX log_class_lookup_idx ON ext_log_entries (object_class)');
        $this->addSql('CREATE INDEX log_date_lookup_idx ON ext_log_entries (logged_at)');
        $this->addSql('CREATE INDEX log_user_lookup_idx ON ext_log_entries (username)');
        $this->addSql('CREATE INDEX log_version_lookup_idx ON ext_log_entries (object_id, object_class, version)');
        $this->addSql('COMMENT ON COLUMN ext_log_entries.data IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE ext_translations (id SERIAL NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX lookup_unique_idx ON ext_translations (foreign_key, locale, object_class, field)');
        $this->addSql('CREATE TABLE mission (id INT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, short_description TEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, image_data BYTEA DEFAULT NULL, image_mime_type VARCHAR(255) DEFAULT NULL, start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, enddate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, required_volunteers INT NOT NULL, meeting_point VARCHAR(255) DEFAULT NULL, calendar_event_description VARCHAR(255) DEFAULT NULL, is_enabled BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9067F23C166D1F9C ON mission (project_id)');
        $this->addSql('CREATE TABLE organisation (id INT NOT NULL, name VARCHAR(255) NOT NULL, public BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE person (id INT NOT NULL, lastname VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, mobile VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, remark VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE person_organisation (person_id INT NOT NULL, organisation_id INT NOT NULL, PRIMARY KEY(person_id, organisation_id))');
        $this->addSql('CREATE INDEX IDX_5EFD2F9217BBB47 ON person_organisation (person_id)');
        $this->addSql('CREATE INDEX IDX_5EFD2F99E6B1585 ON person_organisation (organisation_id)');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, organisation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, domain VARCHAR(255) DEFAULT NULL, domain_proof_key VARCHAR(255) DEFAULT NULL, is_enabled BOOLEAN DEFAULT false NOT NULL, enrollment_settings JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE9E6B1585 ON project (organisation_id)');
        $this->addSql('CREATE TABLE user_organisation (id INT NOT NULL, appuser_id INT NOT NULL, organisation_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_662D4EB6BB5E5996 ON user_organisation (appuser_id)');
        $this->addSql('CREATE INDEX IDX_662D4EB69E6B1585 ON user_organisation (organisation_id)');
        $this->addSql('ALTER TABLE appuser ADD CONSTRAINT FK_EE8A7C74945BDC54 FOREIGN KEY (selected_organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1D9B149A4 FOREIGN KEY (mission_choice01_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1CB04E64A FOREIGN KEY (mission_choice02_id) REFERENCES mission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_organisation ADD CONSTRAINT FK_5EFD2F9217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person_organisation ADD CONSTRAINT FK_5EFD2F99E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE9E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_organisation ADD CONSTRAINT FK_662D4EB6BB5E5996 FOREIGN KEY (appuser_id) REFERENCES appuser (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_organisation ADD CONSTRAINT FK_662D4EB69E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE appuser_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE communication_template_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE enrollment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ext_log_entries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mission_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE organisation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE person_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_organisation_id_seq CASCADE');
        $this->addSql('ALTER TABLE appuser DROP CONSTRAINT FK_EE8A7C74945BDC54');
        $this->addSql('ALTER TABLE enrollment DROP CONSTRAINT FK_DBDCD7E1D9B149A4');
        $this->addSql('ALTER TABLE enrollment DROP CONSTRAINT FK_DBDCD7E1CB04E64A');
        $this->addSql('ALTER TABLE enrollment DROP CONSTRAINT FK_DBDCD7E1166D1F9C');
        $this->addSql('ALTER TABLE mission DROP CONSTRAINT FK_9067F23C166D1F9C');
        $this->addSql('ALTER TABLE person_organisation DROP CONSTRAINT FK_5EFD2F9217BBB47');
        $this->addSql('ALTER TABLE person_organisation DROP CONSTRAINT FK_5EFD2F99E6B1585');
        $this->addSql('ALTER TABLE project DROP CONSTRAINT FK_2FB3D0EE9E6B1585');
        $this->addSql('ALTER TABLE user_organisation DROP CONSTRAINT FK_662D4EB6BB5E5996');
        $this->addSql('ALTER TABLE user_organisation DROP CONSTRAINT FK_662D4EB69E6B1585');
        $this->addSql('DROP TABLE appuser');
        $this->addSql('DROP TABLE communication_template');
        $this->addSql('DROP TABLE enrollment');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_organisation');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE user_organisation');
    }
}
