<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240117151832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting ADD COLUMN email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE setting ADD COLUMN copyright VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__setting AS SELECT id, website_name, description, currency, taxe_rate, logo, street, city, code_postal, state, updated_at, created_at, phone_number, facebook_link, youtube_link, instagram_link FROM setting');
        $this->addSql('DROP TABLE setting');
        $this->addSql('CREATE TABLE setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, website_name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, taxe_rate INTEGER DEFAULT NULL, logo VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, code_postal VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , phone_number VARCHAR(14) DEFAULT NULL, facebook_link VARCHAR(255) DEFAULT NULL, youtube_link VARCHAR(255) DEFAULT NULL, instagram_link VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO setting (id, website_name, description, currency, taxe_rate, logo, street, city, code_postal, state, updated_at, created_at, phone_number, facebook_link, youtube_link, instagram_link) SELECT id, website_name, description, currency, taxe_rate, logo, street, city, code_postal, state, updated_at, created_at, phone_number, facebook_link, youtube_link, instagram_link FROM __temp__setting');
        $this->addSql('DROP TABLE __temp__setting');
    }
}
