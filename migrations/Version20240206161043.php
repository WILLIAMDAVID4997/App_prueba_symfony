<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206161043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alumnos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(100) NOT NULL, correo_electronico VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alumnos_cursos (alumnos_id INT NOT NULL, cursos_id INT NOT NULL, INDEX IDX_CB85D7C6A03F5ABF (alumnos_id), INDEX IDX_CB85D7C66AFE7B9A (cursos_id), PRIMARY KEY(alumnos_id, cursos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cursos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, descripcion LONGTEXT NOT NULL, fecha_inicio DATE NOT NULL, fecha_fin DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alumnos_cursos ADD CONSTRAINT FK_CB85D7C6A03F5ABF FOREIGN KEY (alumnos_id) REFERENCES alumnos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE alumnos_cursos ADD CONSTRAINT FK_CB85D7C66AFE7B9A FOREIGN KEY (cursos_id) REFERENCES cursos (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alumnos_cursos DROP FOREIGN KEY FK_CB85D7C6A03F5ABF');
        $this->addSql('ALTER TABLE alumnos_cursos DROP FOREIGN KEY FK_CB85D7C66AFE7B9A');
        $this->addSql('DROP TABLE alumnos');
        $this->addSql('DROP TABLE alumnos_cursos');
        $this->addSql('DROP TABLE cursos');
    }
}
