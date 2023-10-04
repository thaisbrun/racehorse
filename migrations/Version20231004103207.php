<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004103207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_EquideAnnonce');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_TypeAnnonce');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_UtilisateurAnnonce');
        $this->addSql('ALTER TABLE annonce CHANGE description description VARCHAR(1000) DEFAULT \'NULL\', CHANGE activation activation TINYINT(1) DEFAULT true, CHANGE dateCreation dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE idUtilisateurAnnonce idUtilisateurAnnonce INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5B5B65384 FOREIGN KEY (idEquideA) REFERENCES equide (idEquide) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E586239BAD FOREIGN KEY (idTypeA) REFERENCES typeannonce (idTypeAnnonce) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C1A211B6 FOREIGN KEY (idUtilisateurAnnonce) REFERENCES utilisateur (idUtilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_RegionDep');
        $this->addSql('ALTER TABLE departement CHANGE libelle libelle VARCHAR(30) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63C2D0FDE3 FOREIGN KEY (idRegionDep) REFERENCES region (idRegion) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideRace');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideDep');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideRobe');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideProprio');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_TypeEquide');
        $this->addSql('ALTER TABLE equide CHANGE nom nom VARCHAR(40) DEFAULT \'NULL\', CHANGE dateNaiss dateNaiss DATETIME DEFAULT \'NULL\', CHANGE lienHN lienHN VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_AE76D40C9EAA7E4 FOREIGN KEY (robe) REFERENCES robe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_AE76D40DA6FBBAF FOREIGN KEY (race) REFERENCES race (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_AE76D40398D97ED FOREIGN KEY (idTypeEq) REFERENCES typeequide (idTypeEquide) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_AE76D40D95849B7 FOREIGN KEY (idDep) REFERENCES departement (idDepartement) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_AE76D40694D9874 FOREIGN KEY (idProprio) REFERENCES utilisateur (idUtilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_idAnnonceFav_idAnnonce');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_idUtilisateurFav_idUtilisateur');
        $this->addSql('DROP INDEX FK_idAnnonceFav_idAnnonce ON favoris');
        $this->addSql('DROP INDEX `primary` ON favoris');
        $this->addSql('ALTER TABLE favoris CHANGE idUtilisateurFav idUtilisateurFav INT DEFAULT NULL, CHANGE dateCreation dateCreation DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432F036B63 FOREIGN KEY (idUtilisateurFav) REFERENCES utilisateur (idUtilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C4322266D67A FOREIGN KEY (idAnnonceFav) REFERENCES annonce (idAnnonce) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD PRIMARY KEY (idAnnonceFav)');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_ImageAnnonce');
        $this->addSql('ALTER TABLE image CHANGE lienImage lienImage VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F3D32232F FOREIGN KEY (idAnnonceImage) REFERENCES annonce (idAnnonce)');
        $this->addSql('ALTER TABLE race CHANGE libelle libelle VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE region CHANGE libelle libelle VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE requete DROP FOREIGN KEY FK_Requete');
        $this->addSql('ALTER TABLE requete CHANGE objet objet VARCHAR(30) DEFAULT \'NULL\', CHANGE description description VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE requete ADD CONSTRAINT FK_1E6639AACBDFCEE9 FOREIGN KEY (idAuteurRequete) REFERENCES utilisateur (idUtilisateur) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE robe CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE libelle libelle VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE typeannonce CHANGE libelle libelle VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE typeequide CHANGE libelle libelle VARCHAR(20) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5B5B65384');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E586239BAD');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C1A211B6');
        $this->addSql('ALTER TABLE annonce CHANGE description description VARCHAR(1000) DEFAULT NULL, CHANGE activation activation TINYINT(1) DEFAULT 1, CHANGE dateCreation dateCreation DATE DEFAULT \'CURRENT_TIMESTAMP\', CHANGE idUtilisateurAnnonce idUtilisateurAnnonce INT NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_EquideAnnonce FOREIGN KEY (idEquideA) REFERENCES equide (idEquide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_TypeAnnonce FOREIGN KEY (idTypeA) REFERENCES typeannonce (idTypeAnnonce)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_UtilisateurAnnonce FOREIGN KEY (idUtilisateurAnnonce) REFERENCES utilisateur (idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63C2D0FDE3');
        $this->addSql('ALTER TABLE departement CHANGE libelle libelle VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_RegionDep FOREIGN KEY (idRegionDep) REFERENCES region (idRegion)');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40C9EAA7E4');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40DA6FBBAF');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40398D97ED');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40D95849B7');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40694D9874');
        $this->addSql('ALTER TABLE equide CHANGE nom nom VARCHAR(40) DEFAULT NULL, CHANGE dateNaiss dateNaiss DATE DEFAULT NULL, CHANGE lienHN lienHN VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideRace FOREIGN KEY (race) REFERENCES race (id)');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideDep FOREIGN KEY (idDep) REFERENCES departement (idDepartement)');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideRobe FOREIGN KEY (robe) REFERENCES robe (id)');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideProprio FOREIGN KEY (idProprio) REFERENCES utilisateur (idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_TypeEquide FOREIGN KEY (idTypeEq) REFERENCES typeequide (idTypeEquide)');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432F036B63');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C4322266D67A');
        $this->addSql('DROP INDEX `PRIMARY` ON favoris');
        $this->addSql('ALTER TABLE favoris CHANGE dateCreation dateCreation DATE DEFAULT NULL, CHANGE idUtilisateurFav idUtilisateurFav INT NOT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_idAnnonceFav_idAnnonce FOREIGN KEY (idAnnonceFav) REFERENCES annonce (idAnnonce) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_idUtilisateurFav_idUtilisateur FOREIGN KEY (idUtilisateurFav) REFERENCES utilisateur (idUtilisateur) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_idAnnonceFav_idAnnonce ON favoris (idAnnonceFav)');
        $this->addSql('ALTER TABLE favoris ADD PRIMARY KEY (idUtilisateurFav, idAnnonceFav)');
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
