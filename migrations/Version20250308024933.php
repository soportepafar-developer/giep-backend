<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250308024933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE competencia360 (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre LONGTEXT NOT NULL, descripcion LONGTEXT DEFAULT NULL, tipo VARCHAR(255) NOT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) NOT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) NOT NULL, INDEX IDX_8968257F521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instrumento360 (id INT AUTO_INCREMENT NOT NULL, tipounidad_id INT DEFAULT NULL, many_to_one_id INT NOT NULL, empresa_id INT DEFAULT NULL, nombre LONGTEXT NOT NULL, descripcion LONGTEXT DEFAULT NULL, fecha_vigencia DATETIME DEFAULT NULL, fecha_publicacion DATETIME DEFAULT NULL, publicar TINYINT(1) DEFAULT NULL, duracion INT NOT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_14E39D42650459E5 (tipounidad_id), INDEX IDX_14E39D42EAB5DEB (many_to_one_id), INDEX IDX_14E39D42521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instrumento360_evaluaciones (id INT AUTO_INCREMENT NOT NULL, id_instrumento_usuario_id INT NOT NULL, competencia360_id INT NOT NULL, nivel_dominio_id INT NOT NULL, empresa_id INT NOT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_9F1077C0AC44FF46 (id_instrumento_usuario_id), INDEX IDX_9F1077C09276D359 (competencia360_id), INDEX IDX_9F1077C054D42D62 (nivel_dominio_id), INDEX IDX_9F1077C0521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instrumento360_usuarios_asignados (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, unidad_user_id INT NOT NULL, user_evaluador_id INT NOT NULL, unidad_evaluador_id INT DEFAULT NULL, cargo_user_id INT DEFAULT NULL, cargo_evaluador_id INT DEFAULT NULL, instrumento360_id INT NOT NULL, empresa_id INT NOT NULL, create_at DATETIME NOT NULL, create_by VARCHAR(255) NOT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) NOT NULL, INDEX IDX_35E8D707A76ED395 (user_id), INDEX IDX_35E8D707973A9345 (unidad_user_id), INDEX IDX_35E8D707C04D257B (user_evaluador_id), INDEX IDX_35E8D707E693CE82 (unidad_evaluador_id), INDEX IDX_35E8D707AF1187BA (cargo_user_id), INDEX IDX_35E8D707D00BDC3C (cargo_evaluador_id), INDEX IDX_35E8D707E8B3DB1F (instrumento360_id), INDEX IDX_35E8D707521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nivel_dominio (id INT AUTO_INCREMENT NOT NULL, empresa_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, valor INT NOT NULL, descripcion LONGTEXT DEFAULT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_D33E96AA521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_instrumento360 (id INT AUTO_INCREMENT NOT NULL, empresa_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, create_at DATETIME DEFAULT NULL, create_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) NOT NULL, INDEX IDX_983052E2521E1991 (empresa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE competencia360 ADD CONSTRAINT FK_8968257F521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE instrumento360 ADD CONSTRAINT FK_14E39D42650459E5 FOREIGN KEY (tipounidad_id) REFERENCES tipo_unidad (id)');
        $this->addSql('ALTER TABLE instrumento360 ADD CONSTRAINT FK_14E39D42EAB5DEB FOREIGN KEY (many_to_one_id) REFERENCES tipo_instrumento360 (id)');
        $this->addSql('ALTER TABLE instrumento360 ADD CONSTRAINT FK_14E39D42521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE instrumento360_evaluaciones ADD CONSTRAINT FK_9F1077C0AC44FF46 FOREIGN KEY (id_instrumento_usuario_id) REFERENCES instrumento360_usuarios_asignados (id)');
        $this->addSql('ALTER TABLE instrumento360_evaluaciones ADD CONSTRAINT FK_9F1077C09276D359 FOREIGN KEY (competencia360_id) REFERENCES competencia360 (id)');
        $this->addSql('ALTER TABLE instrumento360_evaluaciones ADD CONSTRAINT FK_9F1077C054D42D62 FOREIGN KEY (nivel_dominio_id) REFERENCES nivel_dominio (id)');
        $this->addSql('ALTER TABLE instrumento360_evaluaciones ADD CONSTRAINT FK_9F1077C0521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707973A9345 FOREIGN KEY (unidad_user_id) REFERENCES estructura_organizativa (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707C04D257B FOREIGN KEY (user_evaluador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707E693CE82 FOREIGN KEY (unidad_evaluador_id) REFERENCES estructura_organizativa (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707AF1187BA FOREIGN KEY (cargo_user_id) REFERENCES cargo (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707D00BDC3C FOREIGN KEY (cargo_evaluador_id) REFERENCES cargo (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707E8B3DB1F FOREIGN KEY (instrumento360_id) REFERENCES instrumento360 (id)');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados ADD CONSTRAINT FK_35E8D707521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE nivel_dominio ADD CONSTRAINT FK_D33E96AA521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE tipo_instrumento360 ADD CONSTRAINT FK_983052E2521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrumento360_evaluaciones DROP FOREIGN KEY FK_9F1077C09276D359');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados DROP FOREIGN KEY FK_35E8D707973A9345');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados DROP FOREIGN KEY FK_35E8D707E693CE82');
        $this->addSql('ALTER TABLE instrumento360_usuarios_asignados DROP FOREIGN KEY FK_35E8D707E8B3DB1F');
        $this->addSql('ALTER TABLE instrumento360_evaluaciones DROP FOREIGN KEY FK_9F1077C0AC44FF46');
        $this->addSql('ALTER TABLE instrumento360_evaluaciones DROP FOREIGN KEY FK_9F1077C054D42D62');
        $this->addSql('ALTER TABLE instrumento360 DROP FOREIGN KEY FK_14E39D42EAB5DEB');
        $this->addSql('DROP TABLE competencia360');
        $this->addSql('DROP TABLE instrumento360');
        $this->addSql('DROP TABLE instrumento360_evaluaciones');
        $this->addSql('DROP TABLE instrumento360_usuarios_asignados');
        $this->addSql('DROP TABLE nivel_dominio');
        $this->addSql('DROP TABLE tipo_instrumento360');

    }
}
