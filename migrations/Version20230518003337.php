<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518003337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639097FACD14');
        $this->addSql('ALTER TABLE favorite_recette DROP FOREIGN KEY FK_14EFCDFE5741EEB9');
        $this->addSql('DROP TABLE favorite_recette');
        $this->addSql('DROP INDEX IDX_49BB639097FACD14 ON recette');
        $this->addSql('ALTER TABLE recette DROP favorite_recette_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_recette (id INT AUTO_INCREMENT NOT NULL, fk_user_id INT DEFAULT NULL, INDEX IDX_14EFCDFE5741EEB9 (fk_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorite_recette ADD CONSTRAINT FK_14EFCDFE5741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE recette ADD favorite_recette_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639097FACD14 FOREIGN KEY (favorite_recette_id) REFERENCES favorite_recette (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_49BB639097FACD14 ON recette (favorite_recette_id)');
    }
}
