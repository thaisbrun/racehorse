<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831174448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE activation activation TINYINT(1) DEFAULT true');
        $this->addSql('ALTER TABLE race CHANGE libelle libelle VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE region CHANGE libelle libelle VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE typeannonce CHANGE libelle libelle VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE typeequide CHANGE libelle libelle VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE prenom prenom VARCHAR(20) NOT NULL, CHANGE nom nom VARCHAR(20) NOT NULL, CHANGE login login VARCHAR(20) NOT NULL, CHANGE mail mail VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
  }
}
