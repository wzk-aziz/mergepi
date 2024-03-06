<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240214185719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D9EEA759');
        $this->addSql('DROP INDEX IDX_E11EE94D9EEA759 ON items');
        $this->addSql('ALTER TABLE items DROP inventory_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE items ADD inventory_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D9EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('CREATE INDEX IDX_E11EE94D9EEA759 ON items (inventory_id)');
    }
}
