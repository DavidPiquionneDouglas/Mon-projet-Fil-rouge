<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202185104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches ADD user VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD begin_date DATE NOT NULL, ADD end_date DATE NOT NULL, ADD estimate_enddate DATE NOT NULL, ADD is_finished VARCHAR(255) NOT NULL, ADD is_success VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches DROP user, DROP description, DROP begin_date, DROP end_date, DROP estimate_enddate, DROP is_finished, DROP is_success');
    }
}
