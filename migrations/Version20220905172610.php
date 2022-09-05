<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220905172610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE branch (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, permission_id INT NOT NULL, adress LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_BB861B1F19EB6921 (client_id), INDEX IDX_BB861B1FFED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, short_desc VARCHAR(255) DEFAULT NULL, full_desc LONGTEXT DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, dpo VARCHAR(255) DEFAULT NULL, technical_contact VARCHAR(255) NOT NULL, commercial_contact VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, read_resa TINYINT(1) NOT NULL, edit_resa TINYINT(1) NOT NULL, remove_resa TINYINT(1) NOT NULL, read_payment TINYINT(1) NOT NULL, edit_payment TINYINT(1) NOT NULL, manage_drink TINYINT(1) NOT NULL, add_sub TINYINT(1) NOT NULL, edit_sub TINYINT(1) NOT NULL, remove_sub TINYINT(1) NOT NULL, manage_schedules TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_client (permission_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_E9EA3BEFED90CCA (permission_id), INDEX IDX_E9EA3BE19EB6921 (client_id), PRIMARY KEY(permission_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE branch ADD CONSTRAINT FK_BB861B1F19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE branch ADD CONSTRAINT FK_BB861B1FFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
        $this->addSql('ALTER TABLE permission_client ADD CONSTRAINT FK_E9EA3BEFED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permission_client ADD CONSTRAINT FK_E9EA3BE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD client_id INT DEFAULT NULL, ADD branch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64919EB6921 ON user (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649DCD6CC49 ON user (branch_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DCD6CC49');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919EB6921');
        $this->addSql('ALTER TABLE branch DROP FOREIGN KEY FK_BB861B1F19EB6921');
        $this->addSql('ALTER TABLE branch DROP FOREIGN KEY FK_BB861B1FFED90CCA');
        $this->addSql('ALTER TABLE permission_client DROP FOREIGN KEY FK_E9EA3BEFED90CCA');
        $this->addSql('ALTER TABLE permission_client DROP FOREIGN KEY FK_E9EA3BE19EB6921');
        $this->addSql('DROP TABLE branch');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE permission_client');
        $this->addSql('DROP INDEX UNIQ_8D93D64919EB6921 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649DCD6CC49 ON user');
        $this->addSql('ALTER TABLE user DROP client_id, DROP branch_id');
    }
}
