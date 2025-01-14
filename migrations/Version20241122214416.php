<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241122214416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announcement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_service (idsup INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(255) NOT NULL, emailsup VARCHAR(255) NOT NULL, pnsup INT NOT NULL, issue VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, stater INT NOT NULL, PRIMARY KEY(idsup)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, details VARCHAR(50) DEFAULT NULL, status_offre VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_29D6873EB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, model VARCHAR(50) DEFAULT NULL, qte INT DEFAULT NULL, prix INT DEFAULT NULL, datersv VARCHAR(50) DEFAULT NULL, heurersv VARCHAR(50) DEFAULT NULL, periode INT DEFAULT NULL, impot DOUBLE PRECISION DEFAULT NULL, total DOUBLE PRECISION DEFAULT NULL, mtotal DOUBLE PRECISION DEFAULT NULL, status VARCHAR(25) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responses (idres INT AUTO_INCREMENT NOT NULL, idsup INT NOT NULL, emailsup VARCHAR(255) NOT NULL, reponse VARCHAR(255) NOT NULL, dater DATE NOT NULL, PRIMARY KEY(idres)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE station (id_station INT AUTO_INCREMENT NOT NULL, lieu VARCHAR(250) NOT NULL, name VARCHAR(250) NOT NULL, etat VARCHAR(250) NOT NULL, PRIMARY KEY(id_station)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trotinette (id_trotinette INT AUTO_INCREMENT NOT NULL, id_station INT DEFAULT NULL, vitesse INT NOT NULL, charge INT NOT NULL, couleur VARCHAR(250) NOT NULL, dispo VARCHAR(250) NOT NULL, INDEX IDX_F3E399CE41A451C1 (id_station), PRIMARY KEY(id_trotinette)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utlilisateur (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, num_tel VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, mdp VARCHAR(255) NOT NULL, INDEX role_id (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE trotinette ADD CONSTRAINT FK_F3E399CE41A451C1 FOREIGN KEY (id_station) REFERENCES station (id_station)');
        $this->addSql('ALTER TABLE utlilisateur ADD CONSTRAINT FK_4450FC08D60322AC FOREIGN KEY (role_id) REFERENCES roles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EB83297E7');
        $this->addSql('ALTER TABLE trotinette DROP FOREIGN KEY FK_F3E399CE41A451C1');
        $this->addSql('ALTER TABLE utlilisateur DROP FOREIGN KEY FK_4450FC08D60322AC');
        $this->addSql('DROP TABLE announcement');
        $this->addSql('DROP TABLE customer_service');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE responses');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE station');
        $this->addSql('DROP TABLE trotinette');
        $this->addSql('DROP TABLE utlilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
