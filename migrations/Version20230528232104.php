<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528232104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_favorite_recettes (user_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_B90044F6A76ED395 (user_id), INDEX IDX_B90044F689312FE9 (recette_id), PRIMARY KEY(user_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_favorite_recettes ADD CONSTRAINT FK_B90044F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_favorite_recettes ADD CONSTRAINT FK_B90044F689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_favorite_recettes DROP FOREIGN KEY FK_B90044F6A76ED395');
        $this->addSql('ALTER TABLE user_favorite_recettes DROP FOREIGN KEY FK_B90044F689312FE9');
        $this->addSql('DROP TABLE user_favorite_recettes');
    }
}