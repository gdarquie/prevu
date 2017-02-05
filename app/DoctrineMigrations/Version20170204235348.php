<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170204235348 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book ADD id_author INT DEFAULT NULL, CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3319B986D25 FOREIGN KEY (id_author) REFERENCES author (id_author)');
        $this->addSql('CREATE INDEX IDX_CBE5A3319B986D25 ON book (id_author)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3319B986D25');
        $this->addSql('DROP INDEX IDX_CBE5A3319B986D25 ON book');
        $this->addSql('ALTER TABLE book DROP id_author, CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
