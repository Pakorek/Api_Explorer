<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200722235102 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, birth_date VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actor_program (actor_id INT NOT NULL, program_id INT NOT NULL, INDEX IDX_B01827EE10DAF24A (actor_id), INDEX IDX_B01827EE3EB8070A (program_id), PRIMARY KEY(actor_id, program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, url LONGTEXT NOT NULL, category VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, api_key VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_actor (id INT AUTO_INCREMENT NOT NULL, api_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, birth_date VARCHAR(255) DEFAULT NULL, as_character VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_actor_api_program (api_actor_id INT NOT NULL, api_program_id INT NOT NULL, INDEX IDX_971B6560C3E138C5 (api_actor_id), INDEX IDX_971B6560F9127B1B (api_program_id), PRIMARY KEY(api_actor_id, api_program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_category_api_program (api_category_id INT NOT NULL, api_program_id INT NOT NULL, INDEX IDX_2484C1DE7831176C (api_category_id), INDEX IDX_2484C1DEF9127B1B (api_program_id), PRIMARY KEY(api_category_id, api_program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_creator (id INT AUTO_INCREMENT NOT NULL, api_id VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_creator_api_program (api_creator_id INT NOT NULL, api_program_id INT NOT NULL, INDEX IDX_B704C7C9A68872B7 (api_creator_id), INDEX IDX_B704C7C9F9127B1B (api_program_id), PRIMARY KEY(api_creator_id, api_program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_episode (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, title VARCHAR(255) NOT NULL, released VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, plot LONGTEXT NOT NULL, number INT NOT NULL, INDEX IDX_309468BD4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_program (id INT AUTO_INCREMENT NOT NULL, api_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, plot LONGTEXT NOT NULL, poster VARCHAR(255) NOT NULL, runtime INT NOT NULL, awards LONGTEXT DEFAULT NULL, nb_seasons INT NOT NULL, end_year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_season (id INT AUTO_INCREMENT NOT NULL, program_id INT NOT NULL, year INT NOT NULL, number INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_76131C293EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billing_plan (id INT AUTO_INCREMENT NOT NULL, offer VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_program (category_id INT NOT NULL, program_id INT NOT NULL, INDEX IDX_553537FA12469DE2 (category_id), INDEX IDX_553537FA3EB8070A (program_id), PRIMARY KEY(category_id, program_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creator (id INT AUTO_INCREMENT NOT NULL, program_id INT NOT NULL, full_name VARCHAR(255) NOT NULL, INDEX IDX_BC06EA633EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, title VARCHAR(255) NOT NULL, number INT NOT NULL, synopsis LONGTEXT NOT NULL, released VARCHAR(20) DEFAULT NULL, poster LONGTEXT DEFAULT NULL, INDEX IDX_DDAA1CDA4EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, summary LONGTEXT NOT NULL, poster VARCHAR(255) DEFAULT NULL, api_id VARCHAR(20) DEFAULT NULL, year VARCHAR(20) DEFAULT NULL, runtime INT DEFAULT NULL, awards VARCHAR(255) DEFAULT NULL, nb_seasons INT DEFAULT NULL, end_year INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, program_id INT NOT NULL, number INT NOT NULL, year INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_F0E45BA93EB8070A (program_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, billing_plan_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64983B7894C (billing_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actor_program ADD CONSTRAINT FK_B01827EE10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actor_program ADD CONSTRAINT FK_B01827EE3EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_actor_api_program ADD CONSTRAINT FK_971B6560C3E138C5 FOREIGN KEY (api_actor_id) REFERENCES api_actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_actor_api_program ADD CONSTRAINT FK_971B6560F9127B1B FOREIGN KEY (api_program_id) REFERENCES api_program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_category_api_program ADD CONSTRAINT FK_2484C1DE7831176C FOREIGN KEY (api_category_id) REFERENCES api_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_category_api_program ADD CONSTRAINT FK_2484C1DEF9127B1B FOREIGN KEY (api_program_id) REFERENCES api_program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_creator_api_program ADD CONSTRAINT FK_B704C7C9A68872B7 FOREIGN KEY (api_creator_id) REFERENCES api_creator (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_creator_api_program ADD CONSTRAINT FK_B704C7C9F9127B1B FOREIGN KEY (api_program_id) REFERENCES api_program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE api_episode ADD CONSTRAINT FK_309468BD4EC001D1 FOREIGN KEY (season_id) REFERENCES api_season (id)');
        $this->addSql('ALTER TABLE api_season ADD CONSTRAINT FK_76131C293EB8070A FOREIGN KEY (program_id) REFERENCES api_program (id)');
        $this->addSql('ALTER TABLE category_program ADD CONSTRAINT FK_553537FA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_program ADD CONSTRAINT FK_553537FA3EB8070A FOREIGN KEY (program_id) REFERENCES program (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creator ADD CONSTRAINT FK_BC06EA633EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA93EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64983B7894C FOREIGN KEY (billing_plan_id) REFERENCES billing_plan (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actor_program DROP FOREIGN KEY FK_B01827EE10DAF24A');
        $this->addSql('ALTER TABLE api_actor_api_program DROP FOREIGN KEY FK_971B6560C3E138C5');
        $this->addSql('ALTER TABLE api_category_api_program DROP FOREIGN KEY FK_2484C1DE7831176C');
        $this->addSql('ALTER TABLE api_creator_api_program DROP FOREIGN KEY FK_B704C7C9A68872B7');
        $this->addSql('ALTER TABLE api_actor_api_program DROP FOREIGN KEY FK_971B6560F9127B1B');
        $this->addSql('ALTER TABLE api_category_api_program DROP FOREIGN KEY FK_2484C1DEF9127B1B');
        $this->addSql('ALTER TABLE api_creator_api_program DROP FOREIGN KEY FK_B704C7C9F9127B1B');
        $this->addSql('ALTER TABLE api_season DROP FOREIGN KEY FK_76131C293EB8070A');
        $this->addSql('ALTER TABLE api_episode DROP FOREIGN KEY FK_309468BD4EC001D1');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64983B7894C');
        $this->addSql('ALTER TABLE category_program DROP FOREIGN KEY FK_553537FA12469DE2');
        $this->addSql('ALTER TABLE actor_program DROP FOREIGN KEY FK_B01827EE3EB8070A');
        $this->addSql('ALTER TABLE category_program DROP FOREIGN KEY FK_553537FA3EB8070A');
        $this->addSql('ALTER TABLE creator DROP FOREIGN KEY FK_BC06EA633EB8070A');
        $this->addSql('ALTER TABLE season DROP FOREIGN KEY FK_F0E45BA93EB8070A');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA4EC001D1');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE actor_program');
        $this->addSql('DROP TABLE api');
        $this->addSql('DROP TABLE api_actor');
        $this->addSql('DROP TABLE api_actor_api_program');
        $this->addSql('DROP TABLE api_category');
        $this->addSql('DROP TABLE api_category_api_program');
        $this->addSql('DROP TABLE api_creator');
        $this->addSql('DROP TABLE api_creator_api_program');
        $this->addSql('DROP TABLE api_episode');
        $this->addSql('DROP TABLE api_program');
        $this->addSql('DROP TABLE api_season');
        $this->addSql('DROP TABLE billing_plan');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_program');
        $this->addSql('DROP TABLE creator');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP TABLE user');
    }
}
