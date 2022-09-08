<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220908145013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP INDEX user_id, ADD UNIQUE INDEX UNIQ_C7440455A76ED395 (user_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY client_ibfk_1');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP INDEX UNIQ_C7440455A76ED395, ADD INDEX user_id (user_id)');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT client_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64919EB6921');
    }
}
