<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518230335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE listede_courses_recette (listede_courses_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_14E0E93D73D44D64 (listede_courses_id), INDEX IDX_14E0E93D89312FE9 (recette_id), PRIMARY KEY(listede_courses_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE listede_courses_recette ADD CONSTRAINT FK_14E0E93D73D44D64 FOREIGN KEY (listede_courses_id) REFERENCES listede_courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE listede_courses_recette ADD CONSTRAINT FK_14E0E93D89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE listede_courses_recette DROP FOREIGN KEY FK_14E0E93D73D44D64');
        $this->addSql('ALTER TABLE listede_courses_recette DROP FOREIGN KEY FK_14E0E93D89312FE9');
        $this->addSql('DROP TABLE listede_courses_recette');
    }
}
