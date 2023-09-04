<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904182112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideRace');
        $this->addSql('ALTER TABLE equide DROP FOREIGN KEY FK_EquideRobe');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE race');
        $this->addSql('DROP TABLE robe');
        $this->addSql('DROP INDEX FK_EquideRace ON equide');
        $this->addSql('DROP INDEX FK_EquideRobe ON equide');
        $this->addSql('ALTER TABLE equide CHANGE robe robe VARCHAR(40) DEFAULT \'NULL\', CHANGE race race VARCHAR(40) DEFAULT \'NULL\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE race (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE robe (id INT NOT NULL, libelle VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE equide CHANGE robe robe INT DEFAULT NULL, CHANGE race race INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideRace FOREIGN KEY (race) REFERENCES race (id)');
        $this->addSql('ALTER TABLE equide ADD CONSTRAINT FK_EquideRobe FOREIGN KEY (robe) REFERENCES robe (id)');
        $this->addSql('CREATE INDEX FK_EquideRace ON equide (race)');
        $this->addSql('CREATE INDEX FK_EquideRobe ON equide (robe)');
    }
}
