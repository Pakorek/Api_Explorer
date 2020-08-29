<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821134656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE body_location (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, api_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE body_sublocation (id INT AUTO_INCREMENT NOT NULL, body_location_id INT NOT NULL, name VARCHAR(255) NOT NULL, api_id INT NOT NULL, INDEX IDX_9C9A5342CFD9468E (body_location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptom (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, api_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symptom_body_sublocation (symptom_id INT NOT NULL, body_sublocation_id INT NOT NULL, INDEX IDX_1817548EDEEFDA95 (symptom_id), INDEX IDX_1817548E9413E8CF (body_sublocation_id), PRIMARY KEY(symptom_id, body_sublocation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE body_sublocation ADD CONSTRAINT FK_9C9A5342CFD9468E FOREIGN KEY (body_location_id) REFERENCES body_location (id)');
        $this->addSql('ALTER TABLE symptom_body_sublocation ADD CONSTRAINT FK_1817548EDEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE symptom_body_sublocation ADD CONSTRAINT FK_1817548E9413E8CF FOREIGN KEY (body_sublocation_id) REFERENCES body_sublocation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE body_sublocation DROP FOREIGN KEY FK_9C9A5342CFD9468E');
        $this->addSql('ALTER TABLE symptom_body_sublocation DROP FOREIGN KEY FK_1817548E9413E8CF');
        $this->addSql('ALTER TABLE symptom_body_sublocation DROP FOREIGN KEY FK_1817548EDEEFDA95');
        $this->addSql('DROP TABLE body_location');
        $this->addSql('DROP TABLE body_sublocation');
        $this->addSql('DROP TABLE symptom');
        $this->addSql('DROP TABLE symptom_body_sublocation');
    }
}
