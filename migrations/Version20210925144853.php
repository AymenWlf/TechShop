<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210925144853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE variation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, illustration VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variation_option (id INT AUTO_INCREMENT NOT NULL, variation_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, var_code VARCHAR(255) DEFAULT NULL, INDEX IDX_B17242E05182BFD8 (variation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE variation_option ADD CONSTRAINT FK_B17242E05182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variation_option DROP FOREIGN KEY FK_B17242E05182BFD8');
        $this->addSql('DROP TABLE variation');
        $this->addSql('DROP TABLE variation_option');
    }
}
