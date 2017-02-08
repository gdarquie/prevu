<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170207230217 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrower ADD library INT DEFAULT NULL, ADD koha VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE borrower ADD CONSTRAINT FK_DB904DB4A18098BC FOREIGN KEY (library) REFERENCES library (id_library)');
        $this->addSql('CREATE INDEX IDX_DB904DB4A18098BC ON borrower (library)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE borrower DROP FOREIGN KEY FK_DB904DB4A18098BC');
        $this->addSql('DROP INDEX IDX_DB904DB4A18098BC ON borrower');
        $this->addSql('ALTER TABLE borrower DROP library, DROP koha');
    }
}
