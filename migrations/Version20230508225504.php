<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508225504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_recette (category_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_8E6323EE12469DE2 (category_id), INDEX IDX_8E6323EE89312FE9 (recette_id), PRIMARY KEY(category_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, fk_recette_id INT DEFAULT NULL, text LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C5741EEB9 (fk_user_id), INDEX IDX_9474526C795FE57A (fk_recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, fk_recette_id INT DEFAULT NULL, note INT DEFAULT NULL, INDEX IDX_1323A5755741EEB9 (fk_user_id), INDEX IDX_1323A575795FE57A (fk_recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_recette (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, INDEX IDX_14EFCDFE5741EEB9 (fk_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient_recette (ingredient_id INT NOT NULL, recette_id INT NOT NULL, INDEX IDX_488C6856933FE08C (ingredient_id), INDEX IDX_488C685689312FE9 (recette_id), PRIMARY KEY(ingredient_id, recette_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_de_courses_ingredient (id INT AUTO_INCREMENT NOT NULL, fk_liste_courses_id INT DEFAULT NULL, quantity INT DEFAULT NULL, INDEX IDX_BB542C72BDA9A965 (fk_liste_courses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liste_de_courses_ingredient_ingredient (liste_de_courses_ingredient_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_5FE95AEF640387F6 (liste_de_courses_ingredient_id), INDEX IDX_5FE95AEF933FE08C (ingredient_id), PRIMARY KEY(liste_de_courses_ingredient_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE listede_courses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, favorite_recette_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, preparation_time INT NOT NULL, cooking_time INT NOT NULL, number_portions INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, instruction VARCHAR(255) NOT NULL, INDEX IDX_49BB639097FACD14 (favorite_recette_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_recette ADD CONSTRAINT FK_8E6323EE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_recette ADD CONSTRAINT FK_8E6323EE89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C795FE57A FOREIGN KEY (fk_recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5755741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575795FE57A FOREIGN KEY (fk_recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE favorite_recette ADD CONSTRAINT FK_14EFCDFE5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C6856933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ingredient_recette ADD CONSTRAINT FK_488C685689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient ADD CONSTRAINT FK_BB542C72BDA9A965 FOREIGN KEY (fk_liste_courses_id) REFERENCES listede_courses (id)');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient ADD CONSTRAINT FK_5FE95AEF640387F6 FOREIGN KEY (liste_de_courses_ingredient_id) REFERENCES liste_de_courses_ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient ADD CONSTRAINT FK_5FE95AEF933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639097FACD14 FOREIGN KEY (favorite_recette_id) REFERENCES favorite_recette (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_recette DROP FOREIGN KEY FK_8E6323EE12469DE2');
        $this->addSql('ALTER TABLE category_recette DROP FOREIGN KEY FK_8E6323EE89312FE9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C5741EEB9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C795FE57A');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5755741EEB9');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575795FE57A');
        $this->addSql('ALTER TABLE favorite_recette DROP FOREIGN KEY FK_14EFCDFE5741EEB9');
        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C6856933FE08C');
        $this->addSql('ALTER TABLE ingredient_recette DROP FOREIGN KEY FK_488C685689312FE9');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient DROP FOREIGN KEY FK_BB542C72BDA9A965');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient DROP FOREIGN KEY FK_5FE95AEF640387F6');
        $this->addSql('ALTER TABLE liste_de_courses_ingredient_ingredient DROP FOREIGN KEY FK_5FE95AEF933FE08C');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639097FACD14');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_recette');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE favorite_recette');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE ingredient_recette');
        $this->addSql('DROP TABLE liste_de_courses_ingredient');
        $this->addSql('DROP TABLE liste_de_courses_ingredient_ingredient');
        $this->addSql('DROP TABLE listede_courses');
        $this->addSql('DROP TABLE recette');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
