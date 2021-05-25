<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229214737 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, game_id INT NOT NULL, goals_home SMALLINT DEFAULT NULL, goals_guest SMALLINT NOT NULL, pts SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FBF0EC9BA76ED395 (user_id), INDEX IDX_FBF0EC9BE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, home_id INT NOT NULL, guest_id INT NOT NULL, season_id INT NOT NULL, is_counted TINYINT(1) NOT NULL, date DATETIME NOT NULL, round_nr SMALLINT NOT NULL, goals_home SMALLINT DEFAULT NULL, goals_guest SMALLINT DEFAULT NULL, is_awarded TINYINT(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_232B318CB03A8386 (created_by_id), INDEX IDX_232B318C896DBBDE (updated_by_id), INDEX IDX_232B318C28CDC89C (home_id), INDEX IDX_232B318C9A4AA658 (guest_id), INDEX IDX_232B318C4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, is_active TINYINT(1) NOT NULL, type VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, teams_count SMALLINT NOT NULL, rounds_count SMALLINT NOT NULL, round_games SMALLINT NOT NULL, group_games SMALLINT DEFAULT NULL, INDEX IDX_F0E45BA958AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, is_club TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(3) NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, nation SMALLINT NOT NULL, language VARCHAR(2) NOT NULL, member TINYINT(1) NOT NULL, last_email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C28CDC89C FOREIGN KEY (home_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9A4AA658 FOREIGN KEY (guest_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA958AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9BE48FD905');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA958AFC4DE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C4EC001D1');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C28CDC89C');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C9A4AA658');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9BA76ED395');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CB03A8386');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C896DBBDE');
        $this->addSql('DROP TABLE bet');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
    }
}
