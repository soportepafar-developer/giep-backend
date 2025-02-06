<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412182655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actualiza_nacimiento');
        $this->addSql('DROP TABLE instrumento_captura_user_bk');
        $this->addSql('DROP TABLE tipo_input_evaluacion');
        
        $this->addSql('ALTER TABLE evaluacion ADD CONSTRAINT FK_DEEDCA5342094404 FOREIGN KEY (id_tipo_unidad_id) REFERENCES tipo_unidad (id)');
        $this->addSql('ALTER TABLE evaluacion ADD CONSTRAINT FK_DEEDCA53881ECFA7 FOREIGN KEY (status_id_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE evaluacion ADD CONSTRAINT FK_DEEDCA53D9ACA885 FOREIGN KEY (evaluator_user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluacion_rol ADD CONSTRAINT FK_8C05E765E715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluacion_rol ADD CONSTRAINT FK_8C05E7654BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluacion_usuario ADD CONSTRAINT FK_79F6ACA4593D2563 FOREIGN KEY (id_evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE evaluacion_usuario ADD CONSTRAINT FK_79F6ACA479F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluacion_usuario ADD CONSTRAINT FK_79F6ACA4841DA47 FOREIGN KEY (pais_id_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE evaluacion_usuario ADD CONSTRAINT FK_79F6ACA475BF18A5 FOREIGN KEY (estado_id_id) REFERENCES estado (id)');
        $this->addSql('ALTER TABLE opciones_evaluacion ADD CONSTRAINT FK_38EF335F29B74DE9 FOREIGN KEY (id_pregunta_id) REFERENCES pregunta_evaluacion (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion ADD CONSTRAINT FK_408F5ADE6699F643 FOREIGN KEY (id_input_id) REFERENCES tipo_input (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion ADD CONSTRAINT FK_408F5ADE10560508 FOREIGN KEY (id_categoria_id) REFERENCES tipo_categoria_evaluacion (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion ADD CONSTRAINT FK_408F5ADE593D2563 FOREIGN KEY (id_evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE pregunta_evaluacion ADD CONSTRAINT FK_408F5ADE7A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion_evaluacion (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion ADD CONSTRAINT FK_459698AD79F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion ADD CONSTRAINT FK_459698AD29B74DE9 FOREIGN KEY (id_pregunta_id) REFERENCES pregunta_evaluacion (id)');
        $this->addSql('ALTER TABLE respuesta_evaluacion ADD CONSTRAINT FK_459698AD85880308 FOREIGN KEY (id_opcion_id) REFERENCES opciones_evaluacion (id)');
        $this->addSql('ALTER TABLE seccion_evaluacion ADD CONSTRAINT FK_9C3E60D9E715F406 FOREIGN KEY (evaluacion_id) REFERENCES evaluacion (id)');
        $this->addSql('ALTER TABLE seccion_evaluacion ADD CONSTRAINT FK_9C3E60D96BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE tipo_categoria_evaluacion ADD CONSTRAINT FK_EDB5A1F76BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE area');
        $this->addSql('ALTER TABLE evaluacion DROP FOREIGN KEY FK_DEEDCA5342094404');
        $this->addSql('ALTER TABLE evaluacion DROP FOREIGN KEY FK_DEEDCA53881ECFA7');
        $this->addSql('ALTER TABLE evaluacion DROP FOREIGN KEY FK_DEEDCA53D9ACA885');
        $this->addSql('ALTER TABLE evaluacion_rol DROP FOREIGN KEY FK_8C05E765E715F406');
        $this->addSql('ALTER TABLE evaluacion_rol DROP FOREIGN KEY FK_8C05E7654BAB96C');
        $this->addSql('ALTER TABLE evaluacion_usuario DROP FOREIGN KEY FK_79F6ACA4593D2563');
        $this->addSql('ALTER TABLE evaluacion_usuario DROP FOREIGN KEY FK_79F6ACA479F37AE5');
        $this->addSql('ALTER TABLE evaluacion_usuario DROP FOREIGN KEY FK_79F6ACA4841DA47');
        $this->addSql('ALTER TABLE evaluacion_usuario DROP FOREIGN KEY FK_79F6ACA475BF18A5');
        $this->addSql('ALTER TABLE pregunta_evaluacion DROP FOREIGN KEY FK_408F5ADE6699F643');
        $this->addSql('ALTER TABLE pregunta_evaluacion DROP FOREIGN KEY FK_408F5ADE10560508');
        $this->addSql('ALTER TABLE pregunta_evaluacion DROP FOREIGN KEY FK_408F5ADE593D2563');
        $this->addSql('ALTER TABLE pregunta_evaluacion DROP FOREIGN KEY FK_408F5ADE7A5A413A');
        $this->addSql('ALTER TABLE respuesta_evaluacion DROP FOREIGN KEY FK_459698AD79F37AE5');
        $this->addSql('ALTER TABLE respuesta_evaluacion DROP FOREIGN KEY FK_459698AD29B74DE9');
        $this->addSql('ALTER TABLE respuesta_evaluacion DROP FOREIGN KEY FK_459698AD85880308');
        $this->addSql('ALTER TABLE seccion_evaluacion DROP FOREIGN KEY FK_9C3E60D9E715F406');
        $this->addSql('ALTER TABLE seccion_evaluacion DROP FOREIGN KEY FK_9C3E60D96BF700BD');
        $this->addSql('DROP INDEX idx_8d93d649a0bba62c ON user');
        $this->addSql('DROP INDEX idx_8d93d649df54805d ON user');
    }
}
