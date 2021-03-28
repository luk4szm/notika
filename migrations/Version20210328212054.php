<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210328212054 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `table` (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, team_id INT NOT NULL, division VARCHAR(255) DEFAULT NULL, bracket VARCHAR(255) DEFAULT NULL, place INT DEFAULT NULL, games INT NOT NULL, won INT NOT NULL, drawn INT NOT NULL, lost INT NOT NULL, goals_for INT NOT NULL, goals_against INT NOT NULL, points INT NOT NULL, INDEX IDX_F6298F464EC001D1 (season_id), INDEX IDX_F6298F46296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F464EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE `table` ADD CONSTRAINT FK_F6298F46296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `table`');
    }
}
