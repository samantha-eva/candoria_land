<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116081209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonbons (id INT AUTO_INCREMENT NOT NULL, marque_id INT NOT NULL, sous_categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, poids VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_BE33232B4827B9B2 (marque_id), INDEX IDX_BE33232B365BF48 (sous_categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categories (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_categories_categories (sous_categories_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_F1EFD649F751138 (sous_categories_id), INDEX IDX_F1EFD64A21214B7 (categories_id), PRIMARY KEY(sous_categories_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, nom VARCHAR(180) NOT NULL, prenom VARCHAR(180) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bonbons ADD CONSTRAINT FK_BE33232B4827B9B2 FOREIGN KEY (marque_id) REFERENCES marques (id)');
        $this->addSql('ALTER TABLE bonbons ADD CONSTRAINT FK_BE33232B365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categories (id)');
        $this->addSql('ALTER TABLE sous_categories_categories ADD CONSTRAINT FK_F1EFD649F751138 FOREIGN KEY (sous_categories_id) REFERENCES sous_categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sous_categories_categories ADD CONSTRAINT FK_F1EFD64A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonbons DROP FOREIGN KEY FK_BE33232B4827B9B2');
        $this->addSql('ALTER TABLE bonbons DROP FOREIGN KEY FK_BE33232B365BF48');
        $this->addSql('ALTER TABLE sous_categories_categories DROP FOREIGN KEY FK_F1EFD649F751138');
        $this->addSql('ALTER TABLE sous_categories_categories DROP FOREIGN KEY FK_F1EFD64A21214B7');
        $this->addSql('DROP TABLE bonbons');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE marques');
        $this->addSql('DROP TABLE sous_categories');
        $this->addSql('DROP TABLE sous_categories_categories');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
