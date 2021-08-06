<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210806131211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_photo (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_62EBC13D64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location_translation (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_A2BCA22B64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, category_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_D34A04AD64D218E (location_id), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_CDFC7356727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category_translation (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_1DAAB48712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_photo (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_B5EBFF444584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_translation (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_1846DB704584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, price NUMERIC(10, 2) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_photo (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_5E0B25B354177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room_translation (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_A05CEB5054177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location_photo ADD CONSTRAINT FK_62EBC13D64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE location_translation ADD CONSTRAINT FK_A2BCA22B64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC7356727ACA70 FOREIGN KEY (parent_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_category_translation ADD CONSTRAINT FK_1DAAB48712469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE product_photo ADD CONSTRAINT FK_B5EBFF444584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_translation ADD CONSTRAINT FK_1846DB704584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE room_photo ADD CONSTRAINT FK_5E0B25B354177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE room_translation ADD CONSTRAINT FK_A05CEB5054177093 FOREIGN KEY (room_id) REFERENCES room (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location_photo DROP FOREIGN KEY FK_62EBC13D64D218E');
        $this->addSql('ALTER TABLE location_translation DROP FOREIGN KEY FK_A2BCA22B64D218E');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD64D218E');
        $this->addSql('ALTER TABLE product_photo DROP FOREIGN KEY FK_B5EBFF444584665A');
        $this->addSql('ALTER TABLE product_translation DROP FOREIGN KEY FK_1846DB704584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC7356727ACA70');
        $this->addSql('ALTER TABLE product_category_translation DROP FOREIGN KEY FK_1DAAB48712469DE2');
        $this->addSql('ALTER TABLE room_photo DROP FOREIGN KEY FK_5E0B25B354177093');
        $this->addSql('ALTER TABLE room_translation DROP FOREIGN KEY FK_A05CEB5054177093');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE location_photo');
        $this->addSql('DROP TABLE location_translation');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_category_translation');
        $this->addSql('DROP TABLE product_photo');
        $this->addSql('DROP TABLE product_translation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_photo');
        $this->addSql('DROP TABLE room_translation');
        $this->addSql('DROP TABLE user');
    }
}
