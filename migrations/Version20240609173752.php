<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240609173752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tarea_movilidad (id INT AUTO_INCREMENT NOT NULL, id_tarea_id INT NOT NULL, id_movilidad_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_9E9245643D803374 (id_tarea_id), INDEX IDX_9E924564A54BAD20 (id_movilidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tarea_movilidad ADD CONSTRAINT FK_9E9245643D803374 FOREIGN KEY (id_tarea_id) REFERENCES tarea (id)');
        $this->addSql('ALTER TABLE tarea_movilidad ADD CONSTRAINT FK_9E924564A54BAD20 FOREIGN KEY (id_movilidad_id) REFERENCES movilidad (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea_movilidad DROP FOREIGN KEY FK_9E9245643D803374');
        $this->addSql('ALTER TABLE tarea_movilidad DROP FOREIGN KEY FK_9E924564A54BAD20');
        $this->addSql('DROP TABLE tarea_movilidad');
    }
}
