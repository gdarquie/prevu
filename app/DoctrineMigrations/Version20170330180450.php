<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170330180450 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book ADD custom_nombre INT DEFAULT NULL, ADD custom_discipline INT DEFAULT NULL, CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331E3D4522 FOREIGN KEY (custom_nombre) REFERENCES thesaurus (id_thesaurus)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3314A41D295 FOREIGN KEY (custom_discipline) REFERENCES thesaurus (id_thesaurus)');
        $this->addSql('CREATE INDEX IDX_CBE5A331E3D4522 ON book (custom_nombre)');
        $this->addSql('CREATE INDEX IDX_CBE5A3314A41D295 ON book (custom_discipline)');
        $this->addSql('ALTER TABLE thesaurus CHANGE description description VARCHAR(65535) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331E3D4522');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3314A41D295');
        $this->addSql('DROP INDEX IDX_CBE5A331E3D4522 ON book');
        $this->addSql('DROP INDEX IDX_CBE5A3314A41D295 ON book');
        $this->addSql('ALTER TABLE book DROP custom_nombre, DROP custom_discipline, CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE thesaurus CHANGE description description MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
