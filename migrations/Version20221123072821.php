<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221123072821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE votant (id INT AUTO_INCREMENT NOT NULL, famille_id INT DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, nombre INT DEFAULT NULL, date DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_7685CA3397A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE votant ADD CONSTRAINT FK_7685CA3397A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE votant DROP FOREIGN KEY FK_7685CA3397A77B84');
        $this->addSql('DROP TABLE votant');
    }
}
