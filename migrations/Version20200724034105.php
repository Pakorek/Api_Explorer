<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200724034105 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bug_report (id INT AUTO_INCREMENT NOT NULL, api_id INT NOT NULL, message LONGTEXT NOT NULL, is_fixed TINYINT(1) NOT NULL, INDEX IDX_F6F2DC7A54963938 (api_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bug_report ADD CONSTRAINT FK_F6F2DC7A54963938 FOREIGN KEY (api_id) REFERENCES api (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bug_report');
    }
}
