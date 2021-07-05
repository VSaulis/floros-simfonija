<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705221344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_item (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, category_id INT NOT NULL, price NUMERIC(10, 2) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_D754D550B1E7706E (restaurant_id), INDEX IDX_D754D55012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_CC82849C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_category_translation (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_F83A577112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_photo (id INT AUTO_INCREMENT NOT NULL, menu_item_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_1FBD2B1A9AB44FE0 (menu_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_item_translation (id INT AUTO_INCREMENT NOT NULL, menu_item_id INT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_683EE3A6989D9B62 (slug), INDEX IDX_683EE3A69AB44FE0 (menu_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_photo (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_5EAA37C9B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant_translation (id INT AUTO_INCREMENT NOT NULL, restaurant_id INT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_E5127F75989D9B62 (slug), INDEX IDX_E5127F75B1E7706E (restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D550B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE menu_item ADD CONSTRAINT FK_D754D55012469DE2 FOREIGN KEY (category_id) REFERENCES menu_item_category (id)');
        $this->addSql('ALTER TABLE menu_item_category ADD CONSTRAINT FK_CC82849C727ACA70 FOREIGN KEY (parent_id) REFERENCES menu_item_category (id)');
        $this->addSql('ALTER TABLE menu_item_category_translation ADD CONSTRAINT FK_F83A577112469DE2 FOREIGN KEY (category_id) REFERENCES menu_item_category (id)');
        $this->addSql('ALTER TABLE menu_item_photo ADD CONSTRAINT FK_1FBD2B1A9AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id)');
        $this->addSql('ALTER TABLE menu_item_translation ADD CONSTRAINT FK_683EE3A69AB44FE0 FOREIGN KEY (menu_item_id) REFERENCES menu_item (id)');
        $this->addSql('ALTER TABLE restaurant_photo ADD CONSTRAINT FK_5EAA37C9B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE restaurant_translation ADD CONSTRAINT FK_E5127F75B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_item_photo DROP FOREIGN KEY FK_1FBD2B1A9AB44FE0');
        $this->addSql('ALTER TABLE menu_item_translation DROP FOREIGN KEY FK_683EE3A69AB44FE0');
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D55012469DE2');
        $this->addSql('ALTER TABLE menu_item_category DROP FOREIGN KEY FK_CC82849C727ACA70');
        $this->addSql('ALTER TABLE menu_item_category_translation DROP FOREIGN KEY FK_F83A577112469DE2');
        $this->addSql('ALTER TABLE menu_item DROP FOREIGN KEY FK_D754D550B1E7706E');
        $this->addSql('ALTER TABLE restaurant_photo DROP FOREIGN KEY FK_5EAA37C9B1E7706E');
        $this->addSql('ALTER TABLE restaurant_translation DROP FOREIGN KEY FK_E5127F75B1E7706E');
        $this->addSql('DROP TABLE menu_item');
        $this->addSql('DROP TABLE menu_item_category');
        $this->addSql('DROP TABLE menu_item_category_translation');
        $this->addSql('DROP TABLE menu_item_photo');
        $this->addSql('DROP TABLE menu_item_translation');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE restaurant_photo');
        $this->addSql('DROP TABLE restaurant_translation');
        $this->addSql('DROP TABLE user');
    }
}
