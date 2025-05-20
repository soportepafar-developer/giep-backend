<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520172337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opciones_evaluacion360 (id INT AUTO_INCREMENT NOT NULL, id_pregunta_id INT DEFAULT NULL, idempresa_id INT DEFAULT NULL, nombre VARCHAR(1000) NOT NULL, valor VARCHAR(255) DEFAULT NULL, puntos INT DEFAULT NULL, correcta SMALLINT DEFAULT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) NOT NULL, INDEX IDX_D597D06E29B74DE9 (id_pregunta_id), INDEX IDX_D597D06E9FB704AB (idempresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pregunta_evaluacion360 (id INT AUTO_INCREMENT NOT NULL, id_instrumento_id INT DEFAULT NULL, id_input_id INT DEFAULT NULL, seccion_id INT DEFAULT NULL, idempresa_id INT DEFAULT NULL, id_categoria_id INT DEFAULT NULL, pregunta VARCHAR(1000) NOT NULL, orden SMALLINT DEFAULT NULL, class VARCHAR(255) DEFAULT NULL, obligatorio SMALLINT NOT NULL, puntos VARCHAR(255) DEFAULT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_CCED1017396DC676 (id_instrumento_id), INDEX IDX_CCED10176699F643 (id_input_id), INDEX IDX_CCED10177A5A413A (seccion_id), INDEX IDX_CCED10179FB704AB (idempresa_id), INDEX IDX_CCED101710560508 (id_categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE respuesta_evaluacion360 (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_pregunta_id INT NOT NULL, id_opcion_id INT DEFAULT NULL, idempresa_id INT DEFAULT NULL, entrada_texto VARCHAR(2000) DEFAULT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_7FDDB1779F37AE5 (id_user_id), INDEX IDX_7FDDB1729B74DE9 (id_pregunta_id), INDEX IDX_7FDDB1785880308 (id_opcion_id), INDEX IDX_7FDDB179FB704AB (idempresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seccion_evaluacion360 (id INT AUTO_INCREMENT NOT NULL, status_id INT DEFAULT NULL, idempresa_id INT DEFAULT NULL, nombre VARCHAR(255) DEFAULT NULL, orden INT DEFAULT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) NOT NULL, INDEX IDX_506D5B2D6BF700BD (status_id), INDEX IDX_506D5B2D9FB704AB (idempresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_input_evaluacion360 (id INT AUTO_INCREMENT NOT NULL, estatus_id INT DEFAULT NULL, idempresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, seleccion_multiple SMALLINT NOT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_136C1DD159BAB351 (estatus_id), INDEX IDX_136C1DD19FB704AB (idempresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE opciones_evaluacion360 ADD CONSTRAINT FK_D597D06E29B74DE9 FOREIGN KEY (id_pregunta_id) REFERENCES pregunta_evaluacion360 (id)');
        $this->addSql('ALTER TABLE opciones_evaluacion360 ADD CONSTRAINT FK_D597D06E9FB704AB FOREIGN KEY (idempresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 ADD CONSTRAINT FK_CCED1017396DC676 FOREIGN KEY (id_instrumento_id) REFERENCES instrumento360_evaluaciones (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 ADD CONSTRAINT FK_CCED10176699F643 FOREIGN KEY (id_input_id) REFERENCES tipo_input_evaluacion360 (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 ADD CONSTRAINT FK_CCED10177A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion_evaluacion360 (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 ADD CONSTRAINT FK_CCED10179FB704AB FOREIGN KEY (idempresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 ADD CONSTRAINT FK_CCED101710560508 FOREIGN KEY (id_categoria_id) REFERENCES competencia360 (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion360 ADD CONSTRAINT FK_7FDDB1779F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion360 ADD CONSTRAINT FK_7FDDB1729B74DE9 FOREIGN KEY (id_pregunta_id) REFERENCES pregunta_evaluacion360 (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion360 ADD CONSTRAINT FK_7FDDB1785880308 FOREIGN KEY (id_opcion_id) REFERENCES opciones_evaluacion360 (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion360 ADD CONSTRAINT FK_7FDDB179FB704AB FOREIGN KEY (idempresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE seccion_evaluacion360 ADD CONSTRAINT FK_506D5B2D6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE seccion_evaluacion360 ADD CONSTRAINT FK_506D5B2D9FB704AB FOREIGN KEY (idempresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE tipo_input_evaluacion360 ADD CONSTRAINT FK_136C1DD159BAB351 FOREIGN KEY (estatus_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE tipo_input_evaluacion360 ADD CONSTRAINT FK_136C1DD19FB704AB FOREIGN KEY (idempresa_id) REFERENCES empresa (id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE respuesta_evaluacion360 DROP FOREIGN KEY FK_7FDDB1785880308');
        $this->addSql('ALTER TABLE opciones_evaluacion360 DROP FOREIGN KEY FK_D597D06E29B74DE9');
        $this->addSql('ALTER TABLE respuesta_evaluacion360 DROP FOREIGN KEY FK_7FDDB1729B74DE9');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 DROP FOREIGN KEY FK_CCED10177A5A413A');
        $this->addSql('ALTER TABLE pregunta_evaluacion360 DROP FOREIGN KEY FK_CCED10176699F643');

        $this->addSql('DROP TABLE opciones_evaluacion360');
        $this->addSql('DROP TABLE pregunta_evaluacion360');
        $this->addSql('DROP TABLE respuesta_evaluacion360');
        $this->addSql('DROP TABLE seccion_evaluacion360');
        $this->addSql('DROP TABLE tipo_input_evaluacion360');

        
    }
}
