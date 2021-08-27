<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210825071739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_review (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, title VARCHAR(255) NOT NULL, positive_description VARCHAR(255) DEFAULT NULL, negative_description VARCHAR(255) DEFAULT NULL, full_name VARCHAR(255) NOT NULL, visible TINYINT(1) NOT NULL, rating NUMERIC(10, 1) NOT NULL, date DATE NOT NULL, duration INT NOT NULL, updated DATETIME DEFAULT NULL, created DATETIME NOT NULL, INDEX IDX_E5A953A154177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_review ADD CONSTRAINT FK_E5A953A154177093 FOREIGN KEY (room_id) REFERENCES room (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hotel_review');
    }
}
