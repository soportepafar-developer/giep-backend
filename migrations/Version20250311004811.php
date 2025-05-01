<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311004811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tipo_instrumento360 ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tipo_instrumento360 ADD CONSTRAINT FK_983052E26BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_983052E26BF700BD ON tipo_instrumento360 (status_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tipo_instrumento360 DROP FOREIGN KEY FK_983052E26BF700BD');
        $this->addSql('DROP INDEX IDX_983052E26BF700BD ON tipo_instrumento360');
        $this->addSql('ALTER TABLE tipo_instrumento360 DROP status_id');
    }
}
