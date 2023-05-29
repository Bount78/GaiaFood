<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517161259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient DROP FOREIGN KEY FK_5FE95AEF640387F6');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient DROP FOREIGN KEY FK_5FE95AEF933FE08C');
        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C685689312FE9');
        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C6856933FE08C');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE liste_de_courses_ingredient_ingredient');
        $this->addSql('DROP TABLE ingredient_recette');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE liste_de_courses_ingredient_ingredient (liste_de_courses_ingredient_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_5FE95AEF640387F6 (liste_de_courses_ingredient_id), INDEX IDX_5FE95AEF933FE08C (ingredient_id), PRIMARY KEY(liste_de_courses_ingredient_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredient_recette (ingredient_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_488C685689312FE9 (recette_id), INDEX IDX_488C6856933FE08C (ingredient_id), PRIMARY KEY(ingredient_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient ADD CONSTRAINT FK_5FE95AEF640387F6 FOREIGN KEY (liste_de_courses_ingredient_id) REFERENCES liste_de_courses_ingredient (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient ADD CONSTRAINT FK_5FE95AEF933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C685689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C6856933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
