<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611072441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movilidad ADD id_coordinador_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movilidad ADD CONSTRAINT FK_81A6C557D937C6DB FOREIGN KEY (id_coordinador_id) REFERENCES coordinador (id)');
        $this->addSql('CREATE INDEX IDX_81A6C557D937C6DB ON movilidad (id_coordinador_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movilidad DROP FOREIGN KEY FK_81A6C557D937C6DB');
        $this->addSql('DROP INDEX IDX_81A6C557D937C6DB ON movilidad');
        $this->addSql('ALTER TABLE movilidad DROP id_coordinador_id');
    }
}
