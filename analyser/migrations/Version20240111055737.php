<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240111055737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE upwork_job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE upwork_locations_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE upwork_skill_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE source_job_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE source_job_industry_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE source_skill_id_seq CASCADE');
        $this->addSql('CREATE TABLE jobs (id UUID NOT NULL, source_id VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, title TEXT NOT NULL, description TEXT NOT NULL, extra JSON DEFAULT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, stage INT NOT NULL, company VARCHAR(255) DEFAULT NULL, salary VARCHAR(255) DEFAULT NULL, contract_type VARCHAR(255) DEFAULT NULL, location TEXT DEFAULT NULL, industry VARCHAR(255) DEFAULT NULL, employment_type VARCHAR(255) DEFAULT NULL, link TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX unique_job_per_stage ON jobs (stage, source, source_id)');
        $this->addSql('COMMENT ON COLUMN jobs.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN jobs.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE source_job_skills (id UUID NOT NULL, job_source_id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F9814AD5528CF370 ON source_job_skills (job_source_id)');
        $this->addSql('COMMENT ON COLUMN source_job_skills.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_job_skills.job_source_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_job_skills.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE source_jobs (id UUID NOT NULL, stage_id INT NOT NULL, source_id VARCHAR(255) NOT NULL, title TEXT NOT NULL, description TEXT NOT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, link TEXT NOT NULL, source VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, employment_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A919E8C62298D193 ON source_jobs (stage_id)');
        $this->addSql('COMMENT ON COLUMN source_jobs.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_jobs.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE source_jobs_industry (id UUID NOT NULL, job_source_id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B487F4ED528CF370 ON source_jobs_industry (job_source_id)');
        $this->addSql('COMMENT ON COLUMN source_jobs_industry.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_jobs_industry.job_source_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN source_jobs_industry.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE stage (id INT NOT NULL, title VARCHAR(255) NOT NULL, stage_date_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN stage.stage_date_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE upwork_job_skills (id UUID NOT NULL, job_upwork_id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CF16F59E11856010 ON upwork_job_skills (job_upwork_id)');
        $this->addSql('COMMENT ON COLUMN upwork_job_skills.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN upwork_job_skills.job_upwork_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN upwork_job_skills.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE upwork_jobs (id UUID NOT NULL, stage_id INT NOT NULL, source_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, posted_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, link TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A5E202962298D193 ON upwork_jobs (stage_id)');
        $this->addSql('CREATE UNIQUE INDEX unique_upwork_jobs ON upwork_jobs (stage_id, source_id)');
        $this->addSql('COMMENT ON COLUMN upwork_jobs.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN upwork_jobs.posted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE upwork_locations (id UUID NOT NULL, job_upwork_id UUID NOT NULL, title VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB8D64DE11856010 ON upwork_locations (job_upwork_id)');
        $this->addSql('COMMENT ON COLUMN upwork_locations.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN upwork_locations.job_upwork_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN upwork_locations.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE upwork_users (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, users_total VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN upwork_users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE source_job_skills ADD CONSTRAINT FK_F9814AD5528CF370 FOREIGN KEY (job_source_id) REFERENCES source_jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_jobs ADD CONSTRAINT FK_A919E8C62298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE source_jobs_industry ADD CONSTRAINT FK_B487F4ED528CF370 FOREIGN KEY (job_source_id) REFERENCES source_jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE upwork_job_skills ADD CONSTRAINT FK_CF16F59E11856010 FOREIGN KEY (job_upwork_id) REFERENCES upwork_jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE upwork_jobs ADD CONSTRAINT FK_A5E202962298D193 FOREIGN KEY (stage_id) REFERENCES stage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE upwork_locations ADD CONSTRAINT FK_EB8D64DE11856010 FOREIGN KEY (job_upwork_id) REFERENCES upwork_jobs (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE upwork_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE upwork_locations_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE upwork_skill_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE source_job_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE source_job_industry_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE source_skill_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE source_job_skills DROP CONSTRAINT FK_F9814AD5528CF370');
        $this->addSql('ALTER TABLE source_jobs DROP CONSTRAINT FK_A919E8C62298D193');
        $this->addSql('ALTER TABLE source_jobs_industry DROP CONSTRAINT FK_B487F4ED528CF370');
        $this->addSql('ALTER TABLE upwork_job_skills DROP CONSTRAINT FK_CF16F59E11856010');
        $this->addSql('ALTER TABLE upwork_jobs DROP CONSTRAINT FK_A5E202962298D193');
        $this->addSql('ALTER TABLE upwork_locations DROP CONSTRAINT FK_EB8D64DE11856010');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE source_job_skills');
        $this->addSql('DROP TABLE source_jobs');
        $this->addSql('DROP TABLE source_jobs_industry');
        $this->addSql('DROP TABLE stage');
        $this->addSql('DROP TABLE upwork_job_skills');
        $this->addSql('DROP TABLE upwork_jobs');
        $this->addSql('DROP TABLE upwork_locations');
        $this->addSql('DROP TABLE upwork_users');
    }
}
