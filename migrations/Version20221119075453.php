<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221119075453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, concours_id INT DEFAULT NULL, famille_id INT DEFAULT NULL, telephone VARCHAR(10) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_5A108564D11E3C7 (concours_id), INDEX IDX_5A10856497A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564D11E3C7 FOREIGN KEY (concours_id) REFERENCES concours (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856497A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564D11E3C7');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856497A77B84');
        $this->addSql('DROP TABLE vote');
    }
}
