<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203095712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches ADD estimate_end_date DATETIME DEFAULT NULL, DROP estimate_enddate, CHANGE description description LONGTEXT NOT NULL, CHANGE is_finished is_finished TINYINT(1) NOT NULL, CHANGE is_success is_success TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches ADD estimate_enddate DATETIME NOT NULL, DROP estimate_end_date, CHANGE description description VARCHAR(255) NOT NULL, CHANGE is_finished is_finished VARCHAR(255) NOT NULL, CHANGE is_success is_success VARCHAR(255) NOT NULL');
    }
}
