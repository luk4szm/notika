<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520210618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ranking (id INT AUTO_INCREMENT NOT NULL, pts DOUBLE PRECISION NOT NULL, hit INT NOT NULL, typed_rounds INT NOT NULL, typed_games INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ranking_user (ranking_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_24AE59C320F64684 (ranking_id), INDEX IDX_24AE59C3A76ED395 (user_id), PRIMARY KEY(ranking_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ranking_user ADD CONSTRAINT FK_24AE59C320F64684 FOREIGN KEY (ranking_id) REFERENCES ranking (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ranking_user ADD CONSTRAINT FK_24AE59C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranking_user DROP FOREIGN KEY FK_24AE59C320F64684');
        $this->addSql('DROP TABLE ranking');
        $this->addSql('DROP TABLE ranking_user');
    }
}
