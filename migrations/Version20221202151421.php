<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221202151421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fainaliste (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, finale_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_993E0498131A4F72 (commune_id), INDEX IDX_993E0498D7CF3D08 (finale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fainaliste ADD CONSTRAINT FK_993E0498131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE fainaliste ADD CONSTRAINT FK_993E0498D7CF3D08 FOREIGN KEY (finale_id) REFERENCES finale (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fainaliste DROP FOREIGN KEY FK_993E0498131A4F72');
        $this->addSql('ALTER TABLE fainaliste DROP FOREIGN KEY FK_993E0498D7CF3D08');
        $this->addSql('DROP TABLE fainaliste');
    }
}
