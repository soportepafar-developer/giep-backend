<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317105821 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE competencia_cargo_unidad ADD CONSTRAINT FK_EF43CAF0813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id)');
        $this->addSql('ALTER TABLE competencia_cargo_unidad ADD CONSTRAINT FK_EF43CAF0B105BE34 FOREIGN KEY (dominio_id) REFERENCES nivel_dominio (id)');
        $this->addSql('ALTER TABLE competencia_cargo_unidad ADD CONSTRAINT FK_EF43CAF09980C34D FOREIGN KEY (competencia_id) REFERENCES competencia360 (id)');
        $this->addSql('ALTER TABLE competencia_cargo_unidad ADD CONSTRAINT FK_EF43CAF09D01464C FOREIGN KEY (unidad_id) REFERENCES estructura_organizativa (id)');
        $this->addSql('ALTER TABLE competencia_cargo_unidad ADD CONSTRAINT FK_EF43CAF0521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('CREATE INDEX IDX_EF43CAF0813AC380 ON competencia_cargo_unidad (cargo_id)');
        $this->addSql('CREATE INDEX IDX_EF43CAF0B105BE34 ON competencia_cargo_unidad (dominio_id)');
        $this->addSql('CREATE INDEX IDX_EF43CAF09980C34D ON competencia_cargo_unidad (competencia_id)');
        $this->addSql('CREATE INDEX IDX_EF43CAF09D01464C ON competencia_cargo_unidad (unidad_id)');
        $this->addSql('CREATE INDEX IDX_EF43CAF0521E1991 ON competencia_cargo_unidad (empresa_id)');
     
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE competencia_cargo_unidad MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP FOREIGN KEY FK_EF43CAF0813AC380');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP FOREIGN KEY FK_EF43CAF0B105BE34');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP FOREIGN KEY FK_EF43CAF09980C34D');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP FOREIGN KEY FK_EF43CAF09D01464C');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP FOREIGN KEY FK_EF43CAF0521E1991');
        $this->addSql('DROP INDEX IDX_EF43CAF0813AC380 ON competencia_cargo_unidad');
        $this->addSql('DROP INDEX IDX_EF43CAF0B105BE34 ON competencia_cargo_unidad');
        $this->addSql('DROP INDEX IDX_EF43CAF09980C34D ON competencia_cargo_unidad');
        $this->addSql('DROP INDEX IDX_EF43CAF09D01464C ON competencia_cargo_unidad');
        $this->addSql('DROP INDEX IDX_EF43CAF0521E1991 ON competencia_cargo_unidad');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE competencia_cargo_unidad DROP empresa_id');
    }
}
