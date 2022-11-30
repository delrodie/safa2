<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221125185744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE anomalie (id INT AUTO_INCREMENT NOT NULL, concours_id INT DEFAULT NULL, famille_id INT DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, position INT DEFAULT NULL, INDEX IDX_715AA19CD11E3C7 (concours_id), INDEX IDX_715AA19C97A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE anomalie ADD CONSTRAINT FK_715AA19CD11E3C7 FOREIGN KEY (concours_id) REFERENCES concours (id)');
        $this->addSql('ALTER TABLE anomalie ADD CONSTRAINT FK_715AA19C97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anomalie DROP FOREIGN KEY FK_715AA19CD11E3C7');
        $this->addSql('ALTER TABLE anomalie DROP FOREIGN KEY FK_715AA19C97A77B84');
        $this->addSql('DROP TABLE anomalie');
    }
}
