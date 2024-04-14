<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240413155119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `game` DROP FOREIGN KEY `fk_user_id_user_username`');
        $this->addSql('DROP INDEX `uk_game_user_id` ON `game`');
        $this->addSql('ALTER TABLE `game` DROP `user_id`');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `game` ADD `user_id` VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE `game` ADD CONSTRAINT `fk_user_id_user_username` FOREIGN KEY (`user_id`) REFERENCES `user` (`username`)');
        $this->addSql('CREATE UNIQUE INDEX `uk_game_user_id` ON `game` (`user_id`)');
    }
}
