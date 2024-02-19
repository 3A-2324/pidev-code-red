<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216202331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE suivi_objectif (id INT AUTO_INCREMENT NOT NULL, id_objectif_id INT NOT NULL, id_suivi INT NOT NULL, date_suivi DATE NOT NULL, nouveau_poids INT NOT NULL, commentaire VARCHAR(1000) NOT NULL, INDEX IDX_44C1F8C2D6FD723 (id_objectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE suivi_objectif ADD CONSTRAINT FK_44C1F8C2D6FD723 FOREIGN KEY (id_objectif_id) REFERENCES objectif (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE suivi_objectif DROP FOREIGN KEY FK_44C1F8C2D6FD723');
        $this->addSql('DROP TABLE suivi_objectif');
    }
}
