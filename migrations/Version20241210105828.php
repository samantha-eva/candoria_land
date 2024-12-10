<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210105828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE marques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bonbons ADD marque_id INT NOT NULL AFTER categorie_id');
        $this->addSql('ALTER TABLE bonbons ADD CONSTRAINT FK_BE33232B4827B9B2 FOREIGN KEY (marque_id) REFERENCES marques (id)');
        $this->addSql('CREATE INDEX IDX_BE33232B4827B9B2 ON bonbons (marque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonbons DROP FOREIGN KEY FK_BE33232B4827B9B2');
        $this->addSql('DROP TABLE marques');
        $this->addSql('DROP INDEX IDX_BE33232B4827B9B2 ON bonbons');
        $this->addSql('ALTER TABLE bonbons DROP marque_id');
    }
}
