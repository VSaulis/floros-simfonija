<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025104500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_photo ADD position INT NOT NULL, DROP featured');
        $this->addSql('ALTER TABLE banquet_hall ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE banquet_hall_photo ADD position INT NOT NULL, DROP featured');
        $this->addSql('ALTER TABLE gallery ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE gallery_photo ADD position INT NOT NULL, DROP featured');
        $this->addSql('ALTER TABLE hotel ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE hotel_photo ADD position INT NOT NULL, DROP featured');
        $this->addSql('ALTER TABLE location ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE location_photo ADD position INT NOT NULL, DROP featured');
        $this->addSql('ALTER TABLE room ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE room_photo ADD position INT NOT NULL, DROP featured');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_photo ADD featured TINYINT(1) NOT NULL, DROP position');
        $this->addSql('ALTER TABLE banquet_hall DROP position');
        $this->addSql('ALTER TABLE banquet_hall_photo ADD featured TINYINT(1) NOT NULL, DROP position');
        $this->addSql('ALTER TABLE gallery DROP position');
        $this->addSql('ALTER TABLE gallery_photo ADD featured TINYINT(1) NOT NULL, DROP position');
        $this->addSql('ALTER TABLE hotel DROP position');
        $this->addSql('ALTER TABLE hotel_photo ADD featured TINYINT(1) NOT NULL, DROP position');
        $this->addSql('ALTER TABLE location DROP position');
        $this->addSql('ALTER TABLE location_photo ADD featured TINYINT(1) NOT NULL, DROP position');
        $this->addSql('ALTER TABLE room DROP position');
        $this->addSql('ALTER TABLE room_photo ADD featured TINYINT(1) NOT NULL, DROP position');
    }
}
