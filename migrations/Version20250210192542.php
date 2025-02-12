<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210192542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archivos_extesionesd DROP idempresa_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE archivos_extesionesd ADD CONSTRAINT FK_4118287B8092C4D1 FOREIGN KEY (id_tipo_archivos_id) REFERENCES tipo_archivod (id)');
        $this->addSql('ALTER TABLE archivos_extesionesd ADD CONSTRAINT FK_4118287BC7E67E71 FOREIGN KEY (id_status_tipo_archivo_id) REFERENCES tipo_status_archivod (id)');
        $this->addSql('CREATE INDEX IDX_4118287B8092C4D1 ON archivos_extesionesd (id_tipo_archivos_id)');
        $this->addSql('CREATE INDEX IDX_4118287BC7E67E71 ON archivos_extesionesd (id_status_tipo_archivo_id)');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_188D04B33E33389B');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_188D04B3AC371557');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_188D04B3E141EDFE');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_188D04B3F5FBF9F2');
        $this->addSql('DROP INDEX idx_188d04b33e33389b ON archivosd');
        $this->addSql('CREATE INDEX IDX_CAAD25FE3E33389B ON archivosd (id_tipo_archivo_id)');
        $this->addSql('DROP INDEX idx_188d04b3f5fbf9f2 ON archivosd');
        $this->addSql('CREATE INDEX IDX_CAAD25FEF5FBF9F2 ON archivosd (id_limited_bloqueo_id)');
        $this->addSql('DROP INDEX idx_188d04b3e141edfe ON archivosd');
        $this->addSql('CREATE INDEX IDX_CAAD25FEE141EDFE ON archivosd (idestado_id)');
        $this->addSql('DROP INDEX idx_188d04b3ac371557 ON archivosd');
        $this->addSql('CREATE INDEX IDX_CAAD25FEAC371557 ON archivosd (id_control_archivo_digital_id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_188D04B33E33389B FOREIGN KEY (id_tipo_archivo_id) REFERENCES tipo_archivod (id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_188D04B3AC371557 FOREIGN KEY (id_control_archivo_digital_id) REFERENCES control_archivo_digital (id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_188D04B3E141EDFE FOREIGN KEY (idestado_id) REFERENCES tipo_estadod (id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_188D04B3F5FBF9F2 FOREIGN KEY (id_limited_bloqueo_id) REFERENCES tipo_limited_bloqueod (id)');
        $this->addSql('ALTER TABLE control_archivo_digital DROP FOREIGN KEY FK_F5FD0BC262BDC5EC');
        $this->addSql('DROP INDEX fk_f5fd0bc262bdc5ec ON control_archivo_digital');
        $this->addSql('CREATE INDEX IDX_F5FD0BC262BDC5EC ON control_archivo_digital (idubica4_id)');
        $this->addSql('ALTER TABLE control_archivo_digital ADD CONSTRAINT FK_F5FD0BC262BDC5EC FOREIGN KEY (idubica4_id) REFERENCES ubicacion (id)');
        $this->addSql('ALTER TABLE estructura_organizativa CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('DROP INDEX IDX_7054EC75786A81FB ON hist_archivo_bloqueadod');
        $this->addSql('ALTER TABLE hist_archivo_bloqueadod DROP iduser_id, DROP idempresa_id, CHANGE fecha_desbloqueo fecha_desbloqueo DATETIME NOT NULL');
        $this->addSql('ALTER TABLE hist_archivo_bloqueadod ADD CONSTRAINT FK_B8C29B938B42E401 FOREIGN KEY (idarchivo_id) REFERENCES archivosd (id)');
        $this->addSql('DROP INDEX idx_7054ec758b42e401 ON hist_archivo_bloqueadod');
        $this->addSql('CREATE INDEX IDX_B8C29B938B42E401 ON hist_archivo_bloqueadod (idarchivo_id)');
        $this->addSql('DROP INDEX IDX_CBB3A58D786A81FB ON hist_archivo_cont_versiond');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond CHANGE iduser iduser INT DEFAULT NULL, CHANGE fecha_creacion_hist fecha_creacion_hist DATETIME DEFAULT NULL, CHANGE comentario comentario VARCHAR(500) DEFAULT NULL, CHANGE nombre_original nombre_original VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond ADD CONSTRAINT FK_B1F06F4CA5A2869 FOREIGN KEY (id_orientacion_id) REFERENCES tipo_orientaciond (id)');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond ADD CONSTRAINT FK_B1F06F48B42E401 FOREIGN KEY (idarchivo_id) REFERENCES archivosd (id)');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond ADD CONSTRAINT FK_B1F06F47B74D29 FOREIGN KEY (id_tipo_operaciones_id) REFERENCES tipo_operacionesd (id)');
        $this->addSql('DROP INDEX idx_cbb3a58dca5a2869 ON hist_archivo_cont_versiond');
        $this->addSql('CREATE INDEX IDX_B1F06F4CA5A2869 ON hist_archivo_cont_versiond (id_orientacion_id)');
        $this->addSql('DROP INDEX idx_cbb3a58d8b42e401 ON hist_archivo_cont_versiond');
        $this->addSql('CREATE INDEX IDX_B1F06F48B42E401 ON hist_archivo_cont_versiond (idarchivo_id)');
        $this->addSql('DROP INDEX idx_cbb3a58d7b74d29 ON hist_archivo_cont_versiond');
        $this->addSql('CREATE INDEX IDX_B1F06F47B74D29 ON hist_archivo_cont_versiond (id_tipo_operaciones_id)');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod DROP idempresa_id');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod ADD CONSTRAINT FK_E5DEC856FBF8E41B FOREIGN KEY (id_tipo_archivo_ext_id) REFERENCES archivos_extesionesd (id)');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod ADD CONSTRAINT FK_E5DEC856C7E67E71 FOREIGN KEY (id_status_tipo_archivo_id) REFERENCES tipo_archivod (id)');
        $this->addSql('DROP INDEX idx_41c2b8ffbf8e41b ON tamano_archivo_permitidod');
        $this->addSql('CREATE INDEX IDX_E5DEC856FBF8E41B ON tamano_archivo_permitidod (id_tipo_archivo_ext_id)');
        $this->addSql('DROP INDEX idx_41c2b8fc7e67e71 ON tamano_archivo_permitidod');
        $this->addSql('CREATE INDEX IDX_E5DEC856C7E67E71 ON tamano_archivo_permitidod (id_status_tipo_archivo_id)');
        $this->addSql('ALTER TABLE tipo_almacen ADD create_at DATETIME DEFAULT NULL, ADD create_by VARCHAR(255) DEFAULT NULL, ADD update_at DATETIME DEFAULT NULL, ADD update_by VARCHAR(255) DEFAULT NULL, ADD idempresa_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_EB980159786A81FB ON usuario_archivo_bloqueadod');
        $this->addSql('ALTER TABLE usuario_archivo_bloqueadod DROP iduser_id, DROP idempresa_id');
        $this->addSql('ALTER TABLE usuario_archivo_bloqueadod ADD CONSTRAINT FK_8A813B9D8B42E401 FOREIGN KEY (idarchivo_id) REFERENCES archivosd (id)');
        $this->addSql('DROP INDEX idx_eb9801598b42e401 ON usuario_archivo_bloqueadod');
        $this->addSql('CREATE INDEX IDX_8A813B9D8B42E401 ON usuario_archivo_bloqueadod (idarchivo_id)');
        $this->addSql('ALTER TABLE usuario_archivosd CHANGE idusuario_id idusuario_id INT DEFAULT NULL, CHANGE idempresa_id idempresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_archivosd ADD CONSTRAINT FK_4396CFEC730F69E5 FOREIGN KEY (iduserarchivos_id) REFERENCES archivosd (id)');
        $this->addSql('CREATE INDEX IDX_4396CFEC730F69E5 ON usuario_archivosd (iduserarchivos_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE archivos_extesionesd MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE archivos_extesionesd DROP FOREIGN KEY FK_4118287B8092C4D1');
        $this->addSql('ALTER TABLE archivos_extesionesd DROP FOREIGN KEY FK_4118287BC7E67E71');
        $this->addSql('DROP INDEX IDX_4118287B8092C4D1 ON archivos_extesionesd');
        $this->addSql('DROP INDEX IDX_4118287BC7E67E71 ON archivos_extesionesd');
        $this->addSql('ALTER TABLE archivos_extesionesd DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE archivos_extesionesd ADD idempresa_id INT DEFAULT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_CAAD25FE3E33389B');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_CAAD25FEF5FBF9F2');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_CAAD25FEE141EDFE');
        $this->addSql('ALTER TABLE archivosd DROP FOREIGN KEY FK_CAAD25FEAC371557');
        $this->addSql('DROP INDEX idx_caad25feac371557 ON archivosd');
        $this->addSql('CREATE INDEX IDX_188D04B3AC371557 ON archivosd (id_control_archivo_digital_id)');
        $this->addSql('DROP INDEX idx_caad25fe3e33389b ON archivosd');
        $this->addSql('CREATE INDEX IDX_188D04B33E33389B ON archivosd (id_tipo_archivo_id)');
        $this->addSql('DROP INDEX idx_caad25fef5fbf9f2 ON archivosd');
        $this->addSql('CREATE INDEX IDX_188D04B3F5FBF9F2 ON archivosd (id_limited_bloqueo_id)');
        $this->addSql('DROP INDEX idx_caad25fee141edfe ON archivosd');
        $this->addSql('CREATE INDEX IDX_188D04B3E141EDFE ON archivosd (idestado_id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_CAAD25FE3E33389B FOREIGN KEY (id_tipo_archivo_id) REFERENCES tipo_archivod (id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_CAAD25FEF5FBF9F2 FOREIGN KEY (id_limited_bloqueo_id) REFERENCES tipo_limited_bloqueod (id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_CAAD25FEE141EDFE FOREIGN KEY (idestado_id) REFERENCES tipo_estadod (id)');
        $this->addSql('ALTER TABLE archivosd ADD CONSTRAINT FK_CAAD25FEAC371557 FOREIGN KEY (id_control_archivo_digital_id) REFERENCES control_archivo_digital (id)');
        $this->addSql('ALTER TABLE control_archivo_digital DROP FOREIGN KEY FK_F5FD0BC262BDC5EC');
        $this->addSql('DROP INDEX idx_f5fd0bc262bdc5ec ON control_archivo_digital');
        $this->addSql('CREATE INDEX FK_F5FD0BC262BDC5EC ON control_archivo_digital (idubica4_id)');
        $this->addSql('ALTER TABLE control_archivo_digital ADD CONSTRAINT FK_F5FD0BC262BDC5EC FOREIGN KEY (idubica4_id) REFERENCES ubicacion (id)');
        $this->addSql('ALTER TABLE estructura_organizativa CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE hist_archivo_bloqueadod DROP FOREIGN KEY FK_B8C29B938B42E401');
        $this->addSql('ALTER TABLE hist_archivo_bloqueadod DROP FOREIGN KEY FK_B8C29B938B42E401');
        $this->addSql('ALTER TABLE hist_archivo_bloqueadod ADD iduser_id INT NOT NULL, ADD idempresa_id INT DEFAULT NULL, CHANGE fecha_desbloqueo fecha_desbloqueo DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_7054EC75786A81FB ON hist_archivo_bloqueadod (iduser_id)');
        $this->addSql('DROP INDEX idx_b8c29b938b42e401 ON hist_archivo_bloqueadod');
        $this->addSql('CREATE INDEX IDX_7054EC758B42E401 ON hist_archivo_bloqueadod (idarchivo_id)');
        $this->addSql('ALTER TABLE hist_archivo_bloqueadod ADD CONSTRAINT FK_B8C29B938B42E401 FOREIGN KEY (idarchivo_id) REFERENCES archivosd (id)');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond DROP FOREIGN KEY FK_B1F06F4CA5A2869');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond DROP FOREIGN KEY FK_B1F06F48B42E401');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond DROP FOREIGN KEY FK_B1F06F47B74D29');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond DROP FOREIGN KEY FK_B1F06F4CA5A2869');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond DROP FOREIGN KEY FK_B1F06F48B42E401');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond DROP FOREIGN KEY FK_B1F06F47B74D29');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond CHANGE iduser iduser INT NOT NULL, CHANGE fecha_creacion_hist fecha_creacion_hist DATETIME NOT NULL, CHANGE comentario comentario VARCHAR(2000) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nombre_original nombre_original VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE INDEX IDX_CBB3A58D786A81FB ON hist_archivo_cont_versiond (iduser)');
        $this->addSql('DROP INDEX idx_b1f06f47b74d29 ON hist_archivo_cont_versiond');
        $this->addSql('CREATE INDEX IDX_CBB3A58D7B74D29 ON hist_archivo_cont_versiond (id_tipo_operaciones_id)');
        $this->addSql('DROP INDEX idx_b1f06f4ca5a2869 ON hist_archivo_cont_versiond');
        $this->addSql('CREATE INDEX IDX_CBB3A58DCA5A2869 ON hist_archivo_cont_versiond (id_orientacion_id)');
        $this->addSql('DROP INDEX idx_b1f06f48b42e401 ON hist_archivo_cont_versiond');
        $this->addSql('CREATE INDEX IDX_CBB3A58D8B42E401 ON hist_archivo_cont_versiond (idarchivo_id)');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond ADD CONSTRAINT FK_B1F06F4CA5A2869 FOREIGN KEY (id_orientacion_id) REFERENCES tipo_orientaciond (id)');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond ADD CONSTRAINT FK_B1F06F48B42E401 FOREIGN KEY (idarchivo_id) REFERENCES archivosd (id)');
        $this->addSql('ALTER TABLE hist_archivo_cont_versiond ADD CONSTRAINT FK_B1F06F47B74D29 FOREIGN KEY (id_tipo_operaciones_id) REFERENCES tipo_operacionesd (id)');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod DROP FOREIGN KEY FK_E5DEC856FBF8E41B');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod DROP FOREIGN KEY FK_E5DEC856C7E67E71');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod DROP FOREIGN KEY FK_E5DEC856FBF8E41B');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod DROP FOREIGN KEY FK_E5DEC856C7E67E71');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod ADD idempresa_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX idx_e5dec856fbf8e41b ON tamano_archivo_permitidod');
        $this->addSql('CREATE INDEX IDX_41C2B8FFBF8E41B ON tamano_archivo_permitidod (id_tipo_archivo_ext_id)');
        $this->addSql('DROP INDEX idx_e5dec856c7e67e71 ON tamano_archivo_permitidod');
        $this->addSql('CREATE INDEX IDX_41C2B8FC7E67E71 ON tamano_archivo_permitidod (id_status_tipo_archivo_id)');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod ADD CONSTRAINT FK_E5DEC856FBF8E41B FOREIGN KEY (id_tipo_archivo_ext_id) REFERENCES archivos_extesionesd (id)');
        $this->addSql('ALTER TABLE tamano_archivo_permitidod ADD CONSTRAINT FK_E5DEC856C7E67E71 FOREIGN KEY (id_status_tipo_archivo_id) REFERENCES tipo_archivod (id)');
        $this->addSql('ALTER TABLE tipo_almacen DROP create_at, DROP create_by, DROP update_at, DROP update_by, DROP idempresa_id');
        $this->addSql('ALTER TABLE usuario_archivo_bloqueadod DROP FOREIGN KEY FK_8A813B9D8B42E401');
        $this->addSql('ALTER TABLE usuario_archivo_bloqueadod DROP FOREIGN KEY FK_8A813B9D8B42E401');
        $this->addSql('ALTER TABLE usuario_archivo_bloqueadod ADD iduser_id INT NOT NULL, ADD idempresa_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_EB980159786A81FB ON usuario_archivo_bloqueadod (iduser_id)');
        $this->addSql('DROP INDEX idx_8a813b9d8b42e401 ON usuario_archivo_bloqueadod');
        $this->addSql('CREATE INDEX IDX_EB9801598B42E401 ON usuario_archivo_bloqueadod (idarchivo_id)');
        $this->addSql('ALTER TABLE usuario_archivo_bloqueadod ADD CONSTRAINT FK_8A813B9D8B42E401 FOREIGN KEY (idarchivo_id) REFERENCES archivosd (id)');
        $this->addSql('ALTER TABLE usuario_archivosd DROP FOREIGN KEY FK_4396CFEC730F69E5');
        $this->addSql('DROP INDEX IDX_4396CFEC730F69E5 ON usuario_archivosd');
        $this->addSql('ALTER TABLE usuario_archivosd CHANGE idusuario_id idusuario_id INT NOT NULL, CHANGE idempresa_id idempresa_id INT NOT NULL');
    }
}
