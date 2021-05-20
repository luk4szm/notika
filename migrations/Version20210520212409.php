<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520212409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ranking_info (id INT AUTO_INCREMENT NOT NULL, season_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, mark VARCHAR(1) DEFAULT NULL, start_round INT DEFAULT NULL, end_round INT DEFAULT NULL, created_at DATETIME NOT NULL, close_at DATETIME DEFAULT NULL, INDEX IDX_62B4BEDD4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ranking_info ADD CONSTRAINT FK_62B4BEDD4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ranking_info');
    }
}
