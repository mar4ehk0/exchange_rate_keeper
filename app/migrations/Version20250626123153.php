<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250626123153 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE currency (
                id SERIAL NOT NULL,
                code VARCHAR(255) NOT NULL,
                char VARCHAR(255) NOT NULL,
                nominal INT NOT NULL,
                human_name VARCHAR(255) NOT NULL,
                PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_6956883F77153098 ON currency (code)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE currency_rate (
                id SERIAL NOT NULL,
                currency_id INT NOT NULL,
                value NUMERIC(10, 2) NOT NULL,
                datetime_rate TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_555B7C4D38248176 ON currency_rate (currency_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN currency_rate.datetime_rate IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE currency_rate ADD CONSTRAINT FK_555B7C4D38248176 FOREIGN KEY (currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE currency_rate DROP CONSTRAINT FK_555B7C4D38248176
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE currency
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE currency_rate
        SQL);
    }
}
