<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205214915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches ADD assigned_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE taches ADD CONSTRAINT FK_3BF2CD98ADF66B1A FOREIGN KEY (assigned_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3BF2CD98ADF66B1A ON taches (assigned_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taches DROP FOREIGN KEY FK_3BF2CD98ADF66B1A');
        $this->addSql('DROP INDEX IDX_3BF2CD98ADF66B1A ON taches');
        $this->addSql('ALTER TABLE taches DROP assigned_user_id');
    }
}
