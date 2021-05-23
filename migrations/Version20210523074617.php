<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523074617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_80B839D0A76ED395');
        $this->addSql('DROP INDEX idx_80b839d0a76ed395 ON classification');
        $this->addSql('CREATE INDEX IDX_456BD231A76ED395 ON classification (user_id)');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_80B839D0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_456BD231A76ED395');
        $this->addSql('DROP INDEX idx_456bd231a76ed395 ON classification');
        $this->addSql('CREATE INDEX IDX_80B839D0A76ED395 ON classification (user_id)');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_456BD231A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
