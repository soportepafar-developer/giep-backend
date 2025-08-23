<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722011510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE odis (id INT AUTO_INCREMENT NOT NULL, instrumento360_id INT DEFAULT NULL, user_id INT DEFAULT NULL, user_cargo_id INT DEFAULT NULL, user_evaluador_id INT DEFAULT NULL, cargo_evaluador_id INT DEFAULT NULL, create_at DATETIME DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, update_at DATETIME DEFAULT NULL, update_by VARCHAR(255) DEFAULT NULL, INDEX IDX_70ECABDDE8B3DB1F (instrumento360_id), INDEX IDX_70ECABDDA76ED395 (user_id), INDEX IDX_70ECABDD61B72780 (user_cargo_id), INDEX IDX_70ECABDDC04D257B (user_evaluador_id), INDEX IDX_70ECABDDD00BDC3C (cargo_evaluador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE odis_objetivos (id INT AUTO_INCREMENT NOT NULL, odi_id INT NOT NULL, odirango_id INT NOT NULL, INDEX IDX_81E9405FBFC690A (odi_id), INDEX IDX_81E9405A07AAF5B (odirango_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rangos_odis (id INT AUTO_INCREMENT NOT NULL, rango INT NOT NULL, porcentaje DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE odis ADD CONSTRAINT FK_70ECABDDE8B3DB1F FOREIGN KEY (instrumento360_id) REFERENCES instrumento360 (id)');
        $this->addSql('ALTER TABLE odis ADD CONSTRAINT FK_70ECABDDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE odis ADD CONSTRAINT FK_70ECABDD61B72780 FOREIGN KEY (user_cargo_id) REFERENCES cargo (id)');
        $this->addSql('ALTER TABLE odis ADD CONSTRAINT FK_70ECABDDC04D257B FOREIGN KEY (user_evaluador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE odis ADD CONSTRAINT FK_70ECABDDD00BDC3C FOREIGN KEY (cargo_evaluador_id) REFERENCES cargo (id)');
        $this->addSql('ALTER TABLE odis_objetivos ADD CONSTRAINT FK_81E9405FBFC690A FOREIGN KEY (odi_id) REFERENCES odis (id)');
        $this->addSql('ALTER TABLE odis_objetivos ADD CONSTRAINT FK_81E9405A07AAF5B FOREIGN KEY (odirango_id) REFERENCES rangos_odis (id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
