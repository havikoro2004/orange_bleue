<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831152754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE branch ADD active VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client CHANGE short_desc short_desc VARCHAR(255) DEFAULT NULL, CHANGE dpo dpo VARCHAR(255) DEFAULT NULL, CHANGE logo logo_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD client_id INT DEFAULT NULL, ADD branch_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64919EB6921 ON user (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649DCD6CC49 ON user (branch_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE branch DROP active');
        $this->addSql('ALTER TABLE client CHANGE short_desc short_desc VARCHAR(255) NOT NULL, CHANGE dpo dpo VARCHAR(255) NOT NULL, CHANGE logo_url logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919EB6921');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DCD6CC49');
        $this->addSql('DROP INDEX UNIQ_8D93D64919EB6921 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D649DCD6CC49 ON user');
        $this->addSql('ALTER TABLE user DROP client_id, DROP branch_id');
    }
}
