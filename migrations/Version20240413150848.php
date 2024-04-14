<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240413150848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `user` ADD `game_id` INT DEFAULT NULL
        ');
        $this->addSql('
            ALTER TABLE `user` ADD CONSTRAINT `fk_user_game_id_game_id` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`)
        ');
        $this->addSql('
            ALTER TABLE `game`
                DROP INDEX `ix_game_user_id`,
                ADD UNIQUE INDEX `uk_game_user_id` (`user_id`)
        ');
        $this->addSql('
            CREATE UNIQUE INDEX `uk_user_game_id` ON `user` (`game_id`)
        ');
        $this->addSql('
            ALTER TABLE `game` CHANGE `user_id` `user_id` VARCHAR(32) DEFAULT NULL
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('
            ALTER TABLE `game` CHANGE `user_id` `user_id` VARCHAR(32) NOT NULL
        ');
        $this->addSql('DROP INDEX `uk_user_game_id` ON `user`');
        $this->addSql('
            ALTER TABLE `game`
                DROP INDEX `uk_game_user_id`,
                ADD INDEX `ix_game_user_id` (`user_id`)
        ');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY `fk_user_game_id_game_id`');
        $this->addSql('ALTER TABLE `user` DROP `game_id`');
    }
}
