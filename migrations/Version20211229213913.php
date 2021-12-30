<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229213913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_code ADD used TINYINT(1) NOT NULL, ADD discount INT NOT NULL');
        $this->addSql('ALTER TABLE subscriber DROP INDEX UNIQ_AD005B69A76ED395, ADD INDEX IDX_AD005B69A76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promo_code DROP used, DROP discount');
        $this->addSql('ALTER TABLE subscriber DROP INDEX IDX_AD005B69A76ED395, ADD UNIQUE INDEX UNIQ_AD005B69A76ED395 (user_id)');
    }
}
