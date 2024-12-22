<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241222224405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur ADD roles JSON NOT NULL, DROP role, CHANGE email email VARCHAR(180) NOT NULL, CHANGE mot_de_passe password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_EMAIL ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD role VARCHAR(255) DEFAULT NULL, DROP roles, CHANGE email email VARCHAR(255) NOT NULL, CHANGE password mot_de_passe VARCHAR(255) NOT NULL');
    }
}
