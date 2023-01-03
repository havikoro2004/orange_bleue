<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103103731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE villes_france_free (id INT AUTO_INCREMENT NOT NULL, ville_id INT NOT NULL, ville_departement VARCHAR(3) DEFAULT NULL, ville_slug VARCHAR(255) DEFAULT NULL, ville_nom VARCHAR(45) DEFAULT NULL, ville_nom_simple VARCHAR(45) DEFAULT NULL, ville_nom_reel VARCHAR(45) DEFAULT NULL, ville_nom_soundex VARCHAR(20) DEFAULT NULL, ville_nom_metaphone VARCHAR(22) DEFAULT NULL, ville_code_postal VARCHAR(255) DEFAULT NULL, ville_commune VARCHAR(3) DEFAULT NULL, ville_code_commune VARCHAR(255) DEFAULT NULL, ville_arrondissement INT DEFAULT NULL, ville_canton VARCHAR(4) DEFAULT NULL, ville_amdi INT DEFAULT NULL, ville_population_2010 INT DEFAULT NULL, ville_population_1999 INT DEFAULT NULL, ville_population_2012 INT DEFAULT NULL, ville_densite_2010 INT DEFAULT NULL, ville_surface DOUBLE PRECISION DEFAULT NULL, ville_longitude_deg DOUBLE PRECISION DEFAULT NULL, ville_latitude_deg DOUBLE PRECISION DEFAULT NULL, ville_longitude_grd VARCHAR(9) DEFAULT NULL, ville_latitude_grd VARCHAR(9) DEFAULT NULL, ville_longitude_dms VARCHAR(9) DEFAULT NULL, ville_latitude_dms VARCHAR(9) DEFAULT NULL, ville_zmin INT DEFAULT NULL, ville_zmax INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE villes_france_free');
    }
}
