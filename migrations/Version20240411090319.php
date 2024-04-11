<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240411090319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registro_ejercicio (id INT AUTO_INCREMENT NOT NULL, ejercicio_id INT NOT NULL, usuario_id INT NOT NULL, fecha DATETIME NOT NULL, duracion_minutos INT NOT NULL, INDEX IDX_BD57FC30890A7D (ejercicio_id), INDEX IDX_BD57FCDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registro_ejercicio ADD CONSTRAINT FK_BD57FC30890A7D FOREIGN KEY (ejercicio_id) REFERENCES ejercicio (id)');
        $this->addSql('ALTER TABLE registro_ejercicio ADD CONSTRAINT FK_BD57FCDB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id)');
        $this->addSql('ALTER TABLE ejercicio DROP FOREIGN KEY FK_95ADCFF4DB38439E');
        $this->addSql('DROP INDEX IDX_95ADCFF4DB38439E ON ejercicio');
        $this->addSql('ALTER TABLE ejercicio DROP usuario_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registro_ejercicio DROP FOREIGN KEY FK_BD57FC30890A7D');
        $this->addSql('ALTER TABLE registro_ejercicio DROP FOREIGN KEY FK_BD57FCDB38439E');
        $this->addSql('DROP TABLE registro_ejercicio');
        $this->addSql('ALTER TABLE ejercicio ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE ejercicio ADD CONSTRAINT FK_95ADCFF4DB38439E FOREIGN KEY (usuario_id) REFERENCES usuarios (id)');
        $this->addSql('CREATE INDEX IDX_95ADCFF4DB38439E ON ejercicio (usuario_id)');
    }
}
