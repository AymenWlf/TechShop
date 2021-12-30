<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229124012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_code ADD subscriber_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE promo_code ADD CONSTRAINT FK_3D8C939E7808B1AD FOREIGN KEY (subscriber_id) REFERENCES subscriber (id)');
        $this->addSql('CREATE INDEX IDX_3D8C939E7808B1AD ON promo_code (subscriber_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_code DROP FOREIGN KEY FK_3D8C939E7808B1AD');
        $this->addSql('DROP INDEX IDX_3D8C939E7808B1AD ON promo_code');
        $this->addSql('ALTER TABLE promo_code DROP subscriber_id');
    }
}
