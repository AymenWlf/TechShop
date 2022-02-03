<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220203114846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE carrier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cart_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE confirmation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE contact_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE header_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE modif_password_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE paiement_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE promo_code_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reset_password_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE review_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE subscriber_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE temoignage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE variation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE variation_option_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wish_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(25) NOT NULL, firstname VARCHAR(25) NOT NULL, lastname VARCHAR(25) NOT NULL, company VARCHAR(50) DEFAULT NULL, address TEXT NOT NULL, postal VARCHAR(25) NOT NULL, country VARCHAR(25) NOT NULL, city VARCHAR(25) NOT NULL, phone INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4E6F81A76ED395 ON address (user_id)');
        $this->addSql('CREATE TABLE carrier (id INT NOT NULL, name VARCHAR(25) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cart_item (id INT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0FE25274584665A ON cart_item (product_id)');
        $this->addSql('CREATE INDEX IDX_F0FE2527A76ED395 ON cart_item (user_id)');
        $this->addSql('CREATE TABLE cart_item_variation_option (cart_item_id INT NOT NULL, variation_option_id INT NOT NULL, PRIMARY KEY(cart_item_id, variation_option_id))');
        $this->addSql('CREATE INDEX IDX_B750B14BE9B59A59 ON cart_item_variation_option (cart_item_id)');
        $this->addSql('CREATE INDEX IDX_B750B14BB3E728B6 ON cart_item_variation_option (variation_option_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(25) NOT NULL, illustration VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE confirmation (id INT NOT NULL, user_id INT NOT NULL, cred_conf BOOLEAN DEFAULT NULL, cred_token VARCHAR(255) DEFAULT NULL, cred_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_483D123CA76ED395 ON confirmation (user_id)');
        $this->addSql('CREATE TABLE contact (id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(60) NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4C62E638A76ED395 ON contact (user_id)');
        $this->addSql('CREATE TABLE header (id INT NOT NULL, name VARCHAR(25) NOT NULL, top_cmnt VARCHAR(30) DEFAULT NULL, middle_cmnt VARCHAR(30) NOT NULL, last_cmnt VARCHAR(30) DEFAULT NULL, btn_title VARCHAR(30) NOT NULL, illustration VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE modif_password (id INT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, try INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E2201538A76ED395 ON modif_password (user_id)');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, user_id INT NOT NULL, paiement_method_id INT DEFAULT NULL, promo_code_id INT DEFAULT NULL, created_at VARCHAR(255) NOT NULL, carrier_name VARCHAR(30) NOT NULL, carrier_price DOUBLE PRECISION NOT NULL, reference VARCHAR(255) NOT NULL, session_checkout_id INT NOT NULL, state INT NOT NULL, is_paid BOOLEAN NOT NULL, total DOUBLE PRECISION NOT NULL, livraison TEXT NOT NULL, str_delivery VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)');
        $this->addSql('CREATE INDEX IDX_F5299398DF4E472D ON "order" (paiement_method_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F52993982FAE4625 ON "order" (promo_code_id)');
        $this->addSql('COMMENT ON COLUMN "order".livraison IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE order_details (id INT NOT NULL, my_order_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_845CA2C1BFCDF877 ON order_details (my_order_id)');
        $this->addSql('CREATE INDEX IDX_845CA2C14584665A ON order_details (product_id)');
        $this->addSql('CREATE TABLE paiement_method (id INT NOT NULL, name VARCHAR(60) NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(25) NOT NULL, slug VARCHAR(100) NOT NULL, illustration VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, description TEXT NOT NULL, price INT NOT NULL, is_best BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(product_id, category_id))');
        $this->addSql('CREATE INDEX IDX_CDFC73564584665A ON product_category (product_id)');
        $this->addSql('CREATE INDEX IDX_CDFC735612469DE2 ON product_category (category_id)');
        $this->addSql('CREATE TABLE promo_code (id INT NOT NULL, user_id INT DEFAULT NULL, subscriber_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, code VARCHAR(20) NOT NULL, used BOOLEAN NOT NULL, discount INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D8C939EA76ED395 ON promo_code (user_id)');
        $this->addSql('CREATE INDEX IDX_3D8C939E7808B1AD ON promo_code (subscriber_id)');
        $this->addSql('CREATE TABLE reset_password (id INT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL, date_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, try INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B9983CE5A76ED395 ON reset_password (user_id)');
        $this->addSql('CREATE TABLE review (id INT NOT NULL, product_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(60) NOT NULL, description TEXT NOT NULL, created_at VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_794381C64584665A ON review (product_id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE TABLE subscriber (id INT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AD005B69A76ED395 ON subscriber (user_id)');
        $this->addSql('CREATE TABLE temoignage (id INT NOT NULL, name VARCHAR(50) NOT NULL, temoignage TEXT NOT NULL, illustration VARCHAR(255) NOT NULL, notoriety VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(50) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(25) NOT NULL, pseudoname VARCHAR(30) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE variation (id INT NOT NULL, name VARCHAR(255) NOT NULL, illustration VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE variation_option (id INT NOT NULL, variation_id INT DEFAULT NULL, product_id INT NOT NULL, name VARCHAR(30) NOT NULL, var_code VARCHAR(255) DEFAULT NULL, stock INT NOT NULL, illustration VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B17242E05182BFD8 ON variation_option (variation_id)');
        $this->addSql('CREATE INDEX IDX_B17242E04584665A ON variation_option (product_id)');
        $this->addSql('CREATE TABLE wish_list (id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5B8739BDA76ED395 ON wish_list (user_id)');
        $this->addSql('CREATE TABLE wish_list_product (wish_list_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(wish_list_id, product_id))');
        $this->addSql('CREATE INDEX IDX_9B7C1C9DD69F3311 ON wish_list_product (wish_list_id)');
        $this->addSql('CREATE INDEX IDX_9B7C1C9D4584665A ON wish_list_product (product_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE2527A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item_variation_option ADD CONSTRAINT FK_B750B14BE9B59A59 FOREIGN KEY (cart_item_id) REFERENCES cart_item (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart_item_variation_option ADD CONSTRAINT FK_B750B14BB3E728B6 FOREIGN KEY (variation_option_id) REFERENCES variation_option (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE confirmation ADD CONSTRAINT FK_483D123CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE modif_password ADD CONSTRAINT FK_E2201538A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398DF4E472D FOREIGN KEY (paiement_method_id) REFERENCES paiement_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993982FAE4625 FOREIGN KEY (promo_code_id) REFERENCES promo_code (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C1BFCDF877 FOREIGN KEY (my_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_details ADD CONSTRAINT FK_845CA2C14584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo_code ADD CONSTRAINT FK_3D8C939EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE promo_code ADD CONSTRAINT FK_3D8C939E7808B1AD FOREIGN KEY (subscriber_id) REFERENCES subscriber (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password ADD CONSTRAINT FK_B9983CE5A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C64584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscriber ADD CONSTRAINT FK_AD005B69A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE variation_option ADD CONSTRAINT FK_B17242E05182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE variation_option ADD CONSTRAINT FK_B17242E04584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wish_list_product ADD CONSTRAINT FK_9B7C1C9DD69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE wish_list_product ADD CONSTRAINT FK_9B7C1C9D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart_item_variation_option DROP CONSTRAINT FK_B750B14BE9B59A59');
        $this->addSql('ALTER TABLE product_category DROP CONSTRAINT FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE order_details DROP CONSTRAINT FK_845CA2C1BFCDF877');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398DF4E472D');
        $this->addSql('ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE order_details DROP CONSTRAINT FK_845CA2C14584665A');
        $this->addSql('ALTER TABLE product_category DROP CONSTRAINT FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C64584665A');
        $this->addSql('ALTER TABLE variation_option DROP CONSTRAINT FK_B17242E04584665A');
        $this->addSql('ALTER TABLE wish_list_product DROP CONSTRAINT FK_9B7C1C9D4584665A');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993982FAE4625');
        $this->addSql('ALTER TABLE promo_code DROP CONSTRAINT FK_3D8C939E7808B1AD');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT FK_D4E6F81A76ED395');
        $this->addSql('ALTER TABLE cart_item DROP CONSTRAINT FK_F0FE2527A76ED395');
        $this->addSql('ALTER TABLE confirmation DROP CONSTRAINT FK_483D123CA76ED395');
        $this->addSql('ALTER TABLE contact DROP CONSTRAINT FK_4C62E638A76ED395');
        $this->addSql('ALTER TABLE modif_password DROP CONSTRAINT FK_E2201538A76ED395');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE promo_code DROP CONSTRAINT FK_3D8C939EA76ED395');
        $this->addSql('ALTER TABLE reset_password DROP CONSTRAINT FK_B9983CE5A76ED395');
        $this->addSql('ALTER TABLE review DROP CONSTRAINT FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE subscriber DROP CONSTRAINT FK_AD005B69A76ED395');
        $this->addSql('ALTER TABLE wish_list DROP CONSTRAINT FK_5B8739BDA76ED395');
        $this->addSql('ALTER TABLE variation_option DROP CONSTRAINT FK_B17242E05182BFD8');
        $this->addSql('ALTER TABLE cart_item_variation_option DROP CONSTRAINT FK_B750B14BB3E728B6');
        $this->addSql('ALTER TABLE wish_list_product DROP CONSTRAINT FK_9B7C1C9DD69F3311');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE carrier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cart_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE confirmation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE contact_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE header_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE modif_password_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE order_details_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE paiement_method_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE promo_code_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reset_password_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE review_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE subscriber_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE temoignage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE variation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE variation_option_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE wish_list_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE carrier');
        $this->addSql('DROP TABLE cart_item');
        $this->addSql('DROP TABLE cart_item_variation_option');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE confirmation');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE header');
        $this->addSql('DROP TABLE modif_password');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_details');
        $this->addSql('DROP TABLE paiement_method');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE promo_code');
        $this->addSql('DROP TABLE reset_password');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('DROP TABLE temoignage');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE variation');
        $this->addSql('DROP TABLE variation_option');
        $this->addSql('DROP TABLE wish_list');
        $this->addSql('DROP TABLE wish_list_product');
    }
}
