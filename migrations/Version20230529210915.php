<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529210915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredients DROP FOREIGN KEY FK_B41314063EC4DCE');
        $this->addSql('ALTER TABLE recette_ingredients DROP FOREIGN KEY FK_B413140689312FE9');
        $this->addSql('ALTER TABLE ingredients_recette DROP FOREIGN KEY FK_2B30A3D43EC4DCE');
        $this->addSql('ALTER TABLE ingredients_recette DROP FOREIGN KEY FK_2B30A3D489312FE9');
        $this->addSql('DROP TABLE recette_ingredients');
        $this->addSql('DROP TABLE ingredients_recette');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recette_ingredients (recette_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_B41314063EC4DCE (ingredients_id), INDEX IDX_B413140689312FE9 (recette_id), PRIMARY KEY(recette_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ingredients_recette (ingredients_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_2B30A3D43EC4DCE (ingredients_id), INDEX IDX_2B30A3D489312FE9 (recette_id), PRIMARY KEY(ingredients_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recette_ingredients ADD CONSTRAINT FK_B41314063EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_ingredients ADD CONSTRAINT FK_B413140689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients_recette ADD CONSTRAINT FK_2B30A3D43EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredients_recette ADD CONSTRAINT FK_2B30A3D489312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
