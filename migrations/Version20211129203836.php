<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129203836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiement_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value INT NOT NULL, token_test VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE name');
        $this->addSql('ALTER TABLE `order` ADD paiement_method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DF4E472D FOREIGN KEY (paiement_method_id) REFERENCES paiement_method (id)');
        $this->addSql('CREATE INDEX IDX_F5299398DF4E472D ON `order` (paiement_method_id)');
        $this->addSql('ALTER TABLE variation_option ADD product_id INT NOT NULL, DROP product_slug, DROP product_name');
        $this->addSql('ALTER TABLE variation_option ADD CONSTRAINT FK_B17242E04584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_B17242E04584665A ON variation_option (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DF4E472D');
        $this->addSql('CREATE TABLE name (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE paiement_method');
        $this->addSql('DROP INDEX IDX_F5299398DF4E472D ON `order`');
        $this->addSql('ALTER TABLE `order` DROP paiement_method_id');
        $this->addSql('ALTER TABLE variation_option DROP FOREIGN KEY FK_B17242E04584665A');
        $this->addSql('DROP INDEX IDX_B17242E04584665A ON variation_option');
        $this->addSql('ALTER TABLE variation_option ADD product_slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD product_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP product_id');
    }
}