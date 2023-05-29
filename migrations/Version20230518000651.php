<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518000651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_de_courses_ingredient DROP FOREIGN KEY FK_BB542C72BDA9A965');
        $this->addSql('DROP TABLE liste_de_courses_ingredient');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liste_de_courses_ingredient (id INT AUTO_INCREMENT NOT NULL, fk_liste_courses_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX IDX_BB542C72BDA9A965 (fk_liste_courses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient ADD CONSTRAINT FK_BB542C72BDA9A965 FOREIGN KEY (fk_liste_courses_id) REFERENCES listede_courses (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
