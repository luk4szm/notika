<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210520213433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ranking_user');
        $this->addSql('ALTER TABLE classification ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_80B839D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_80B839D0A76ED395 ON classification (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ranking_user (ranking_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_24AE59C3A76ED395 (user_id), INDEX IDX_24AE59C320F64684 (ranking_id), PRIMARY KEY(ranking_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ranking_user ADD CONSTRAINT FK_24AE59C320F64684 FOREIGN KEY (ranking_id) REFERENCES classification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ranking_user ADD CONSTRAINT FK_24AE59C3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_80B839D0A76ED395');
        $this->addSql('DROP INDEX IDX_80B839D0A76ED395 ON classification');
        $this->addSql('ALTER TABLE classification DROP user_id');
    }
}
