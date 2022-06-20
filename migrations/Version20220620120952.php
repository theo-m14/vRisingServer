<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620120952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE server (id INT AUTO_INCREMENT NOT NULL, user_owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', open_day DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', wipe TINYINT(1) NOT NULL, wipe_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', type TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, clan_size INT NOT NULL, discord VARCHAR(255) DEFAULT NULL, INDEX IDX_5A6DD5F69EB185F9 (user_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE server ADD CONSTRAINT FK_5A6DD5F69EB185F9 FOREIGN KEY (user_owner_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE server');
    }
}
