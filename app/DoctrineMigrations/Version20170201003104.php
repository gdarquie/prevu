<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170201003104 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue DROP FOREIGN KEY FK_12AD233E182A5291');
        $this->addSql('DROP INDEX IDX_12AD233E182A5291 ON issue');
        $this->addSql('ALTER TABLE issue CHANGE idbook id_book INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E40C5BF33 FOREIGN KEY (id_book) REFERENCES book (id_book)');
        $this->addSql('CREATE INDEX IDX_12AD233E40C5BF33 ON issue (id_book)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book CHANGE work_title work_title MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE work_author work_author MEDIUMTEXT DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE issue DROP FOREIGN KEY FK_12AD233E40C5BF33');
        $this->addSql('DROP INDEX IDX_12AD233E40C5BF33 ON issue');
        $this->addSql('ALTER TABLE issue CHANGE id_book idbook INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E182A5291 FOREIGN KEY (idbook) REFERENCES book (id_book)');
        $this->addSql('CREATE INDEX IDX_12AD233E182A5291 ON issue (idbook)');
    }
}
