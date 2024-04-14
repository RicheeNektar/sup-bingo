<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240413092705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE `game` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `user_id` VARCHAR(32) NOT NULL,
                INDEX `ix_game_user_id` (`user_id`)
            )
        ');
        $this->addSql('
            CREATE TABLE `game_text` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `game_id` INT NOT NULL,
                `bingo_text_id` INT NOT NULL,
                `active` TINYINT(1) NOT NULL,
                INDEX `ix_game_text_game_id` (`game_id`),
                INDEX `ix_game_text_bingo_text_id` (`bingo_text_id`)
            )
        ');
        $this->addSql('ALTER TABLE `game` ADD CONSTRAINT `fk_user_id_user_username` FOREIGN KEY (`user_id`) REFERENCES `user` (`username`)');
        $this->addSql('ALTER TABLE `game_text` ADD CONSTRAINT `fk_game_id_game_id` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)');
        $this->addSql('ALTER TABLE `game_text` ADD CONSTRAINT `fk_bingo_text_id_bingo_text_id` FOREIGN KEY (`bingo_text_id`) REFERENCES `bingo_text` (`id`)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `game` DROP FOREIGN KEY `fk_user_id_user_username`');
        $this->addSql('ALTER TABLE `game_text` DROP FOREIGN KEY `fk_game_id_game_id`');
        $this->addSql('ALTER TABLE `game_text` DROP FOREIGN KEY `fk_bingo_text_id_bingo_text_id`');
        $this->addSql('DROP TABLE `game`');
        $this->addSql('DROP TABLE `game_text`');
    }
}
