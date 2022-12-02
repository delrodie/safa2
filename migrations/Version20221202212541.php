<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221202212541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vote_finale (id INT AUTO_INCREMENT NOT NULL, finaliste_id INT DEFAULT NULL, finale_id INT DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, nombre INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, INDEX IDX_91D6BAD99E6398AF (finaliste_id), INDEX IDX_91D6BAD9D7CF3D08 (finale_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote_finale ADD CONSTRAINT FK_91D6BAD99E6398AF FOREIGN KEY (finaliste_id) REFERENCES fainaliste (id)');
        $this->addSql('ALTER TABLE vote_finale ADD CONSTRAINT FK_91D6BAD9D7CF3D08 FOREIGN KEY (finale_id) REFERENCES finale (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vote_finale DROP FOREIGN KEY FK_91D6BAD99E6398AF');
        $this->addSql('ALTER TABLE vote_finale DROP FOREIGN KEY FK_91D6BAD9D7CF3D08');
        $this->addSql('DROP TABLE vote_finale');
    }
}
