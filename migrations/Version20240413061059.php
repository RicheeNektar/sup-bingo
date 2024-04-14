<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240413061059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `user` (
                `username` VARCHAR(32) NOT NULL PRIMARY KEY,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM ("ROLE_ADMIN","ROLE_USER") NOT NULL
            )
        ');
        $this->addSql('
            CREATE TABLE `bingo` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(64) NOT NULL
            )
        ');
        $this->addSql('
            CREATE TABLE `bingo_text` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `bingo_id` INT NOT NULL,
                `text` VARCHAR(255) NOT NULL,
                INDEX `ix_bingo_text_bingo_id` (`bingo_id`)
            )
        ');
        $this->addSql('
            ALTER TABLE `bingo_text` ADD CONSTRAINT `fk_bingo_text_bingo_id` FOREIGN KEY (`bingo_id`) REFERENCES `bingo` (`id`)
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `bingo_text` DROP FOREIGN KEY `fk_bingo_text_bingo_id`');
        $this->addSql('DROP TABLE `bingo_text`');
        $this->addSql('DROP TABLE `bingo`');
        $this->addSql('DROP TABLE `user`');
    }
}
