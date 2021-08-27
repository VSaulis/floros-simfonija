<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827141208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banquet_hall (id INT AUTO_INCREMENT NOT NULL, location_id INT NOT NULL, people_count INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_2B15020D64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banquet_hall_photo (id INT AUTO_INCREMENT NOT NULL, banquet_hall_id INT NOT NULL, featured TINYINT(1) NOT NULL, file_name VARCHAR(255) NOT NULL, file_size INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_B0E26963172D021 (banquet_hall_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banquet_hall_translation (id INT AUTO_INCREMENT NOT NULL, banquet_hall_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, locale VARCHAR(255) NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_ED34196C3172D021 (banquet_hall_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE banquet_hall ADD CONSTRAINT FK_2B15020D64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE banquet_hall_photo ADD CONSTRAINT FK_B0E26963172D021 FOREIGN KEY (banquet_hall_id) REFERENCES banquet_hall (id)');
        $this->addSql('ALTER TABLE banquet_hall_translation ADD CONSTRAINT FK_ED34196C3172D021 FOREIGN KEY (banquet_hall_id) REFERENCES banquet_hall (id)');
        $this->addSql('ALTER TABLE location_photo ADD featured TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE location_translation ADD description LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banquet_hall_photo DROP FOREIGN KEY FK_B0E26963172D021');
        $this->addSql('ALTER TABLE banquet_hall_translation DROP FOREIGN KEY FK_ED34196C3172D021');
        $this->addSql('DROP TABLE banquet_hall');
        $this->addSql('DROP TABLE banquet_hall_photo');
        $this->addSql('DROP TABLE banquet_hall_translation');
        $this->addSql('ALTER TABLE location_photo DROP featured');
        $this->addSql('ALTER TABLE location_translation DROP description');
    }
}
