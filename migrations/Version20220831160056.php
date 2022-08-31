<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831160056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, branch_id INT NOT NULL, members_read TINYINT(1) NOT NULL, members_write TINYINT(1) NOT NULL, members_add TINYINT(1) NOT NULL, members_products_add TINYINT(1) NOT NULL, members_payement_schedules_read TINYINT(1) NOT NULL, members_statistic_read TINYINT(1) NOT NULL, members_subscription_read TINYINT(1) NOT NULL, members_schedules_write TINYINT(1) NOT NULL, payement_schedules_read TINYINT(1) NOT NULL, payement_schedules_write TINYINT(1) NOT NULL, payement_day_read TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_E04992AA19EB6921 (client_id), UNIQUE INDEX UNIQ_E04992AADCD6CC49 (branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AA19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE permission ADD CONSTRAINT FK_E04992AADCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AA19EB6921');
        $this->addSql('ALTER TABLE permission DROP FOREIGN KEY FK_E04992AADCD6CC49');
        $this->addSql('DROP TABLE permission');
    }
}
