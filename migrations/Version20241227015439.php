<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227015439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes CHANGE date_achat date_achat DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contenu_panier DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes CHANGE date_achat date_achat DATETIME NOT NULL');
        $this->addSql('ALTER TABLE contenu_panier ADD date DATETIME NOT NULL');
    }
}
