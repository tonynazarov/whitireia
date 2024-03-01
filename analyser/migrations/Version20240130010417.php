<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240130010417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE source_companies (id UUID NOT NULL, job_source_id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EADA39F4528CF370 ON source_companies (job_source_id)');
        $this->addSql('COMMENT ON COLUMN source_companies.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_companies.job_source_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_companies.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE source_locations (id UUID NOT NULL, job_source_id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F78D974528CF370 ON source_locations (job_source_id)');
        $this->addSql('COMMENT ON COLUMN source_locations.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_locations.job_source_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_locations.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE source_companies ADD CONSTRAINT FK_EADA39F4528CF370 FOREIGN KEY (job_source_id) REFERENCES source_jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_locations ADD CONSTRAINT FK_7F78D974528CF370 FOREIGN KEY (job_source_id) REFERENCES source_jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE stage_id_seq CASCADE');
        $this->addSql('ALTER TABLE source_companies DROP CONSTRAINT FK_EADA39F4528CF370');
        $this->addSql('ALTER TABLE source_locations DROP CONSTRAINT FK_7F78D974528CF370');
        $this->addSql('DROP TABLE source_companies');
        $this->addSql('DROP TABLE source_locations');
    }
}
