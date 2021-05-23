<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523075648 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranking DROP FOREIGN KEY FK_62B4BEDD4EC001D1');
        $this->addSql('DROP INDEX idx_62b4bedd4ec001d1 ON ranking');
        $this->addSql('CREATE INDEX IDX_80B839D04EC001D1 ON ranking (season_id)');
        $this->addSql('ALTER TABLE ranking ADD CONSTRAINT FK_62B4BEDD4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranking DROP FOREIGN KEY FK_80B839D04EC001D1');
        $this->addSql('DROP INDEX idx_80b839d04ec001d1 ON ranking');
        $this->addSql('CREATE INDEX IDX_62B4BEDD4EC001D1 ON ranking (season_id)');
        $this->addSql('ALTER TABLE ranking ADD CONSTRAINT FK_80B839D04EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }
}
