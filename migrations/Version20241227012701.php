<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227012701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes_contenu_panier (commandes_id INT NOT NULL, contenu_panier_id INT NOT NULL, INDEX IDX_8AF7E83F8BF5C2E6 (commandes_id), INDEX IDX_8AF7E83F61405BF (contenu_panier_id), PRIMARY KEY(commandes_id, contenu_panier_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes_contenu_panier ADD CONSTRAINT FK_8AF7E83F8BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_contenu_panier ADD CONSTRAINT FK_8AF7E83F61405BF FOREIGN KEY (contenu_panier_id) REFERENCES contenu_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_produit DROP FOREIGN KEY FK_EF0864778BF5C2E6');
        $this->addSql('ALTER TABLE commandes_produit DROP FOREIGN KEY FK_EF086477F347EFB');
        $this->addSql('DROP TABLE commandes_produit');
        $this->addSql('ALTER TABLE utilisateur CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes_produit (commandes_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_EF0864778BF5C2E6 (commandes_id), INDEX IDX_EF086477F347EFB (produit_id), PRIMARY KEY(commandes_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commandes_produit ADD CONSTRAINT FK_EF0864778BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_produit ADD CONSTRAINT FK_EF086477F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_contenu_panier DROP FOREIGN KEY FK_8AF7E83F8BF5C2E6');
        $this->addSql('ALTER TABLE commandes_contenu_panier DROP FOREIGN KEY FK_8AF7E83F61405BF');
        $this->addSql('DROP TABLE commandes_contenu_panier');
        $this->addSql('ALTER TABLE utilisateur CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
