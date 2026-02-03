<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Ulid;

final class Version20260203210614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user ULIDs and per-bingo leaderboards';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<sql
            ALTER TABLE `user`
                DROP PRIMARY KEY,
                ADD `ulid` BINARY(16) NOT NULL PRIMARY KEY,
                DROP `points`,
                ADD UNIQUE INDEX `uix_user_username` (`username`)
        sql);

        $this->addSql(<<<sql
            CREATE TABLE `user_game_wins` (
                `user_ulid` BINARY(16) NOT NULL,
                `game_id` INT NOT NULL,
                `created_at` TIMESTAMP NOT NULL,
                PRIMARY KEY (`user_ulid`, `game_id`, `created_at`),
                FOREIGN KEY fk_user_game_wins_user_ulid (`user_ulid`) REFERENCES `user` (`ulid`) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY fk_user_game_wins_game_id (`game_id`) REFERENCES `game` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                INDEX ix_user_game_wins_created_at (created_at)
            )
        sql);

        $result = $this->connection->executeQuery(<<<sql
            SELECT `username` FROM `user`;
        sql);

        while ($row = $result->fetchAssociative()) {
            $ulid = Ulid::generate();
            $this->addSql(<<<sql
                UPDATE `user`
                SET `ulid` = '$ulid'
                WHERE `username` = '{$row['username']}';
            sql);
        }
    }

    public function down(Schema $schema): void
    {
        // No turning back
    }
}
