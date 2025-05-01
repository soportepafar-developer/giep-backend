<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250311001806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nivel_dominio ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nivel_dominio ADD CONSTRAINT FK_D33E96AA6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
  
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE nivel_dominio DROP FOREIGN KEY FK_D33E96AA6BF700BD');
        $this->addSql('DROP INDEX IDX_D33E96AA6BF700BD ON nivel_dominio');
        $this->addSql('ALTER TABLE nivel_dominio DROP status_id');
    
    }
}
