<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210523085917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification ADD ranking_id INT NOT NULL');
        $this->addSql('ALTER TABLE classification ADD CONSTRAINT FK_456BD23120F64684 FOREIGN KEY (ranking_id) REFERENCES ranking (id)');
        $this->addSql('CREATE INDEX IDX_456BD23120F64684 ON classification (ranking_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classification DROP FOREIGN KEY FK_456BD23120F64684');
        $this->addSql('DROP INDEX IDX_456BD23120F64684 ON classification');
        $this->addSql('ALTER TABLE classification DROP ranking_id');
    }
}
