<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170201005729 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA9CBE5A331');
        $this->addSql('DROP INDEX IDX_8A90ABA9CBE5A331 ON `key`');
        $this->addSql('ALTER TABLE `key` CHANGE book prevu INT DEFAULT NULL, CHANGE code koha VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9E9D02DCD FOREIGN KEY (prevu) REFERENCES book (id_book)');
        $this->addSql('CREATE INDEX IDX_8A90ABA9E9D02DCD ON `key` (prevu)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA9E9D02DCD');
        $this->addSql('DROP INDEX IDX_8A90ABA9E9D02DCD ON `key`');
        $this->addSql('ALTER TABLE `key` CHANGE prevu book INT DEFAULT NULL, CHANGE koha code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9CBE5A331 FOREIGN KEY (book) REFERENCES book (id_book)');
        $this->addSql('CREATE INDEX IDX_8A90ABA9CBE5A331 ON `key` (book)');
    }
}
