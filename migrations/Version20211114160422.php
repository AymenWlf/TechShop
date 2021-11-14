<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211114160422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE confirmation ADD user_id INT NOT NULL, ADD cred_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_483D123CA76ED395 ON confirmation (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE confirmation DROP FOREIGN KEY FK_483D123CA76ED395');
        $this->addSql('DROP INDEX UNIQ_483D123CA76ED395 ON confirmation');
        $this->addSql('ALTER TABLE confirmation DROP user_id, DROP cred_token');
    }
}
