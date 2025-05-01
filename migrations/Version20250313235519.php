<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313235519 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE competencia_cargo_unidad (id INT AUTO_INCREMENT NOT NULL, cargo_id INT DEFAULT NULL, dominio_id INT DEFAULT NULL, competencia_id INT DEFAULT NULL, unidad_id INT DEFAULT NULL, prioridad INT NOT NULL, INDEX IDX_EF43CAF0813AC380 (cargo_id), INDEX IDX_EF43CAF0B105BE34 (dominio_id), INDEX IDX_EF43CAF09980C34D (competencia_id), INDEX IDX_EF43CAF09D01464C (unidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE competencia_cargo_unidad');
    }
}
