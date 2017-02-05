<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170201002929 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE amazon (id_notice INT AUTO_INCREMENT NOT NULL, tiny_image VARCHAR(45) DEFAULT NULL, medium_image VARCHAR(45) DEFAULT NULL, large_image VARCHAR(45) DEFAULT NULL, edito TEXT DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_notice)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id_author INT AUTO_INCREMENT NOT NULL, firstname LONGTEXT DEFAULT NULL, lastname LONGTEXT DEFAULT NULL, dates LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_author)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id_book INT AUTO_INCREMENT NOT NULL, id_country INT DEFAULT NULL, id_itemtype INT DEFAULT NULL, id_code INT DEFAULT NULL, title MEDIUMTEXT DEFAULT NULL, author MEDIUMTEXT DEFAULT NULL, publicationyear INT DEFAULT NULL, isbn VARCHAR(45) DEFAULT NULL, cdu VARCHAR(45) DEFAULT NULL, dewey VARCHAR(45) DEFAULT NULL, issues INT DEFAULT NULL, total_issues INT DEFAULT NULL, renewals INT DEFAULT NULL, work VARCHAR(255) DEFAULT NULL, work_title MEDIUMTEXT DEFAULT NULL, work_author MEDIUMTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, INDEX IDX_CBE5A3318DEE6016 (id_country), INDEX IDX_CBE5A331767BD974 (id_itemtype), INDEX IDX_CBE5A331FC352C9A (id_code), PRIMARY KEY(id_book)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book_language (id_book INT NOT NULL, id_language INT NOT NULL, INDEX IDX_CD2467EC40C5BF33 (id_book), INDEX IDX_CD2467EC84009D20 (id_language), PRIMARY KEY(id_book, id_language)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrower (id_borrower INT AUTO_INCREMENT NOT NULL, yearofbirth INT DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_borrower)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code (id_code INT AUTO_INCREMENT NOT NULL, code VARCHAR(45) DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_code)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id_country INT AUTO_INCREMENT NOT NULL, pays VARCHAR(45) DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_country)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE issue (id_issue INT AUTO_INCREMENT NOT NULL, id_borrower INT DEFAULT NULL, idbook INT DEFAULT NULL, sex VARCHAR(1) DEFAULT NULL, datedue DATE DEFAULT NULL, issuedate DATE DEFAULT NULL, returndate DATE DEFAULT NULL, renewals INT DEFAULT NULL, niveau VARCHAR(45) DEFAULT NULL, ufr VARCHAR(45) DEFAULT NULL, etape VARCHAR(45) DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, INDEX IDX_12AD233E8B4BA121 (id_borrower), INDEX IDX_12AD233E182A5291 (idbook), PRIMARY KEY(id_issue)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE itemtype (id_itemtype INT AUTO_INCREMENT NOT NULL, itemtype VARCHAR(45) DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_itemtype)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `key` (id_key INT AUTO_INCREMENT NOT NULL, library INT DEFAULT NULL, book INT DEFAULT NULL, code VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, INDEX IDX_8A90ABA9A18098BC (library), INDEX IDX_8A90ABA9CBE5A331 (book), PRIMARY KEY(id_key)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id_language INT AUTO_INCREMENT NOT NULL, language VARCHAR(45) DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_language)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library (id_library INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, last_update DATETIME NOT NULL, PRIMARY KEY(id_library)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3318DEE6016 FOREIGN KEY (id_country) REFERENCES country (id_country)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331767BD974 FOREIGN KEY (id_itemtype) REFERENCES itemtype (id_itemtype)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331FC352C9A FOREIGN KEY (id_code) REFERENCES code (id_code)');
        $this->addSql('ALTER TABLE book_language ADD CONSTRAINT FK_CD2467EC40C5BF33 FOREIGN KEY (id_book) REFERENCES book (id_book)');
        $this->addSql('ALTER TABLE book_language ADD CONSTRAINT FK_CD2467EC84009D20 FOREIGN KEY (id_language) REFERENCES language (id_language)');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E8B4BA121 FOREIGN KEY (id_borrower) REFERENCES borrower (id_borrower)');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E182A5291 FOREIGN KEY (idbook) REFERENCES book (id_book)');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9A18098BC FOREIGN KEY (library) REFERENCES library (id_library)');
        $this->addSql('ALTER TABLE `key` ADD CONSTRAINT FK_8A90ABA9CBE5A331 FOREIGN KEY (book) REFERENCES book (id_book)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book_language DROP FOREIGN KEY FK_CD2467EC40C5BF33');
        $this->addSql('ALTER TABLE issue DROP FOREIGN KEY FK_12AD233E182A5291');
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA9CBE5A331');
        $this->addSql('ALTER TABLE issue DROP FOREIGN KEY FK_12AD233E8B4BA121');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331FC352C9A');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3318DEE6016');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331767BD974');
        $this->addSql('ALTER TABLE book_language DROP FOREIGN KEY FK_CD2467EC84009D20');
        $this->addSql('ALTER TABLE `key` DROP FOREIGN KEY FK_8A90ABA9A18098BC');
        $this->addSql('DROP TABLE amazon');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_language');
        $this->addSql('DROP TABLE borrower');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP TABLE itemtype');
        $this->addSql('DROP TABLE `key`');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE library');
        $this->addSql('DROP TABLE fos_user');
    }
}
