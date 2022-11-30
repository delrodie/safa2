<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221118164444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, commune_id INT DEFAULT NULL, concours_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, vote INT DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, INDEX IDX_2473F213131A4F72 (commune_id), INDEX IDX_2473F213D11E3C7 (concours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213131A4F72 FOREIGN KEY (commune_id) REFERENCES commune (id)');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213D11E3C7 FOREIGN KEY (concours_id) REFERENCES concours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213131A4F72');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213D11E3C7');
        $this->addSql('DROP TABLE famille');
    }
}
