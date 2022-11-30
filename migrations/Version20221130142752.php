<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221130142752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE election (id INT AUTO_INCREMENT NOT NULL, candidat_id INT DEFAULT NULL, scrutin_id INT DEFAULT NULL, operation VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_DCA038008D0EB82 (candidat_id), INDEX IDX_DCA038008D574414 (scrutin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE election ADD CONSTRAINT FK_DCA038008D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE election ADD CONSTRAINT FK_DCA038008D574414 FOREIGN KEY (scrutin_id) REFERENCES scrutin (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE election DROP FOREIGN KEY FK_DCA038008D0EB82');
        $this->addSql('ALTER TABLE election DROP FOREIGN KEY FK_DCA038008D574414');
        $this->addSql('DROP TABLE election');
    }
}
