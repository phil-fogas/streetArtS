<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621104557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS `categorie` (`id` int NOT NULL AUTO_INCREMENT,`name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        
        $this->addSql('CREATE TABLE IF NOT EXISTS `comment` ( `id` int NOT NULL AUTO_INCREMENT,`text` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`id_users` int NOT NULL,`id_street` int NOT NULL,`date_ad` datetime NOT NULL, PRIMARY KEY (`id`), KEY `user_id` (`id_users`), KEY `street_id` (`id_street`) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('CREATE TABLE IF NOT EXISTS `droit` (`id` int NOT NULL AUTO_INCREMENT,`name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`valid` int NOT NULL,`statut` int NOT NULL,PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
       
        $this->addSql('CREATE TABLE IF NOT EXISTS `statut` ( `id` int NOT NULL AUTO_INCREMENT,`statut` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('CREATE TABLE IF NOT EXISTS `street` (`id` int NOT NULL AUTO_INCREMENT,`name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,`adresse` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,`photo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,`description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,`dateCreation` date DEFAULT NULL,`dateFiche` datetime NOT NULL, `valid` int DEFAULT NULL,`statut` int NOT NULL,`id_user` int NOT NULL,`categorie` int NOT NULL,`latitude` float NOT NULL,`longitude` float NOT NULL, PRIMARY KEY (`id`),KEY `user_id_street` (`id_user`), KEY `categoy` (`categorie`) ) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        
        $this->addSql('CREATE TABLE IF NOT EXISTS `users` (`id` int NOT NULL AUTO_INCREMENT, `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`droit` int NOT NULL,`created_at` datetime NOT NULL, `dateVisi` datetime DEFAULT NULL, PRIMARY KEY (`id`)  ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('CREATE TABLE IF NOT EXISTS `mail` (`id` int NOT NULL AUTO_INCREMENT,`email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`sujet` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,`date` datetime DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');

        $this->addSql('CREATE TABLE IF NOT EXISTS `validation` (`id` int NOT NULL AUTO_INCREMENT,`id_user` int NOT NULL, `id_street` int NOT NULL, `chose` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`), KEY `street_id_valid` (`id_street`),KEY `user_id_valid` (`id_user`)) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
