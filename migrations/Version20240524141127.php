<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240524141127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY message_ibfk_3');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY message_ibfk_1');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY message_ibfk_2');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE annonce CHANGE activation activation TINYINT(1) DEFAULT true');
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
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, dateCreation DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, contenu VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateCreation DATE NOT NULL, idChat INT NOT NULL, idAuteur INT NOT NULL, idDestinataire INT NOT NULL, INDEX idAuteur (idAuteur), INDEX idChat (idChat), INDEX idDestinataire (idDestinataire), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT message_ibfk_3 FOREIGN KEY (idDestinataire) REFERENCES utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT message_ibfk_1 FOREIGN KEY (idAuteur) REFERENCES utilisateur (idUtilisateur)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT message_ibfk_2 FOREIGN KEY (idChat) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE annonce CHANGE activation activation TINYINT(1) DEFAULT 1');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40C9EAA7E4');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40DA6FBBAF');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40398D97ED');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40D95849B7');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_AE76D40694D9874');
        $this->addSql('ALTER TABLE equide CHANGE nom nom VARCHAR(40) DEFAULT NULL, CHANGE dateNaiss dateNaiss DATE DEFAULT NULL, CHANGE lienHN lienHN VARCHAR(255) DEFAULT NULL');
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
