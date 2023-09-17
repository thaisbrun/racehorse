<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230917200029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_EquideAnnonce');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_UtilisateurAnnonce');
        $this->addSql('ALTER TABLE annonce CHANGE description description VARCHAR(1000) DEFAULT \'NULL\', CHANGE dateCreation dateCreation DATE DEFAULT \'NULL\', CHANGE idUtilisateurAnnonce idUtilisateurAnnonce INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5B5B65384 FOREIGN KEY (idEquideA) REFERENCES equide (idEquide)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C1A211B6 FOREIGN KEY (idUtilisateurAnnonce) REFERENCES utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE departement CHANGE libelle libelle VARCHAR(30) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideRace');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideRobe');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideProprio');
        $this->addSql('DROP INDEX FK_EquideRace ON equide');
        $this->addSql('DROP INDEX FK_EquideRobe ON equide');
        $this->addSql('ALTER TABLE equide CHANGE robe robe VARCHAR(40) DEFAULT \'NULL\', CHANGE race race VARCHAR(40) DEFAULT \'NULL\', CHANGE nom nom VARCHAR(40) DEFAULT \'NULL\', CHANGE dateNaiss dateNaiss DATETIME DEFAULT \'NULL\', CHANGE lienHN lienHN VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_AE76D40694D9874 FOREIGN KEY (idProprio) REFERENCES utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE favoris CHANGE dateCreation dateCreation DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_ImageAnnonce');
        $this->addSql('ALTER TABLE image CHANGE lienImage lienImage VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F3D32232F FOREIGN KEY (idAnnonceImage) REFERENCES annonce (idAnnonce)');
        $this->addSql('ALTER TABLE race CHANGE libelle libelle VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE region CHANGE libelle libelle VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE requete DROP FOREIGN KEY FK_Requete');
        $this->addSql('ALTER TABLE requete CHANGE objet objet VARCHAR(30) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE requete ADD CONSTRAINT FK_1E6639AACBDFCEE9 FOREIGN KEY (idAuteurRequete) REFERENCES utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE robe CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE libelle libelle VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE typeannonce CHANGE libelle libelle VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE typeequide CHANGE libelle libelle VARCHAR(20) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5B5B65384');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C1A211B6');
        $this->addSql('ALTER TABLE annonce CHANGE description description VARCHAR(1000) DEFAULT NULL, CHANGE dateCreation dateCreation DATE DEFAULT NULL, CHANGE idUtilisateurAnnonce idUtilisateurAnnonce INT NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_EquideAnnonce FOREIGN KEY (idEquideA) REFERENCES equide (idEquide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_UtilisateurAnnonce FOREIGN KEY (idUtilisateurAnnonce) REFERENCES utilisateur (idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE departement CHANGE libelle libelle VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40694D9874');
        $this->addSql('ALTER TABLE equide CHANGE nom nom VARCHAR(40) DEFAULT NULL, CHANGE dateNaiss dateNaiss DATE DEFAULT NULL, CHANGE robe robe INT DEFAULT NULL, CHANGE race race INT DEFAULT NULL, CHANGE lienHN lienHN VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideRace FOREIGN KEY (race) REFERENCES race (id)');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideRobe FOREIGN KEY (robe) REFERENCES robe (id)');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideProprio FOREIGN KEY (idProprio) REFERENCES utilisateur (idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_EquideRace ON equide (race)');
        $this->addSql('CREATE INDEX FK_EquideRobe ON equide (robe)');
        $this->addSql('ALTER TABLE favoris CHANGE dateCreation dateCreation DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F3D32232F');
        $this->addSql('ALTER TABLE image CHANGE lienImage lienImage VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_ImageAnnonce FOREIGN KEY (idAnnonceImage) REFERENCES annonce (idAnnonce) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE race CHANGE libelle libelle VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE region CHANGE libelle libelle VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE requete DROP FOREIGN KEY FK_1E6639AACBDFCEE9');
        $this->addSql('ALTER TABLE requete CHANGE objet objet VARCHAR(30) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requete ADD CONSTRAINT FK_Requete FOREIGN KEY (idAuteurRequete) REFERENCES utilisateur (idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE robe CHANGE id id INT NOT NULL, CHANGE libelle libelle VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE typeannonce CHANGE libelle libelle VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE typeequide CHANGE libelle libelle VARCHAR(20) DEFAULT NULL');
    }
}
