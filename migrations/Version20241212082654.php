<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212082654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, date_achat DATETIME NOT NULL, INDEX IDX_35D4282CFB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes_produit (commandes_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_EF0864778BF5C2E6 (commandes_id), INDEX IDX_EF086477F347EFB (produit_id), PRIMARY KEY(commandes_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commandes_produit ADD CONSTRAINT FK_EF0864778BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_produit ADD CONSTRAINT FK_EF086477F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2FB88E14F');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP INDEX IDX_80507DC0F77D927C ON contenu_panier');
        $this->addSql('ALTER TABLE contenu_panier ADD quantite INT DEFAULT NULL, DROP panier_id, DROP quantité, CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE photo photo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE role role VARCHAR(255) DEFAULT NULL, CHANGE prénom prenom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, date_achat DATETIME NOT NULL, etat TINYINT(1) DEFAULT NULL, INDEX IDX_24CC0DF2FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CFB88E14F');
        $this->addSql('ALTER TABLE commandes_produit DROP FOREIGN KEY FK_EF0864778BF5C2E6');
        $this->addSql('ALTER TABLE commandes_produit DROP FOREIGN KEY FK_EF086477F347EFB');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_produit');
        $this->addSql('ALTER TABLE contenu_panier ADD panier_id INT NOT NULL, ADD quantité INT NOT NULL, DROP quantite, CHANGE produit_id produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE contenu_panier ADD CONSTRAINT FK_80507DC0F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_80507DC0F77D927C ON contenu_panier (panier_id)');
        $this->addSql('ALTER TABLE produit CHANGE photo photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE role role VARCHAR(255) NOT NULL, CHANGE prenom prénom VARCHAR(255) NOT NULL');
    }
}
