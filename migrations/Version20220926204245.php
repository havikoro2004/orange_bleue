<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926204245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE branch ADD adress LONGTEXT NOT NULL, DROP street_name, DROP code_postal, DROP number, DROP city');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE branch ADD street_name VARCHAR(255) NOT NULL, ADD code_postal INT NOT NULL, ADD number INT NOT NULL, ADD city VARCHAR(255) NOT NULL, DROP adress');
    }
}
