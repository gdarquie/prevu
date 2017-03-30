<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BorrowerController extends Controller
{

    /**
     * @Route("/import/borrowers/lib={biblio}", name="import_borrowers")
     */
    public function importBorrowersAction($library)
    {

        $user = $this->container->getParameter('database_user');
        $password = $this->container->getParameter('database_password');
        $host = $this->container->getParameter('database_host');

        switch ($biblio) {
            case "up8":
                $dbname = $this->container->getParameter('database_name2');

                $connectionParams = array(
                    'dbname' => $dbname,
                    'user' => $user,
                    'password' => $password,
                    'host' => $host,
                    'driver' => 'pdo_mysql',
                );
                $library = 1;
                break;

            case "rbx":
                $dbname = $this->container->getParameter('database_name3');
                $connectionParams = array(
                    'dbname' => $dbname,
                    'user' => $user,
                    'password' => $password,
                    'host' => $host,
                    'driver' => 'pdo_mysql',
                );
                $library = 2;

                break;
            default :
                return $this->render('ImportBundle:Book:index.html.twig', array(
                    'count' => $count,
                    'max' => $max
                ));
                break;
        }

        $connectionParamsPrevu = array(
            'dbname' => 'prevu',
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => 'pdo_mysql',
        );

        $config = new \Doctrine\DBAL\Configuration();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $connPrevu = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsPrevu, $config);

        //-----------------------------------------------------------
        //Import des borrowers
        //-----------------------------------------------------------

        $sql = "INSERT INTO borrower(`koha`, `yearofbirth`,`library`, `date_creation`, `last_update`) (SELECT MD5(borrowers.borrowernumber), YEAR(dateofbirth), :library, NOW(), NOW() FROM koha.borrowers)"; //les borrowers ont une libary seulement (permet de supprimer
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("library", $library);
        $stmt->execute();


        //Roubaix
        $sql = "INSERT INTO borrower(`koha`, `yearofbirth`,`library`, `date_creation`, `last_update`) (SELECT borrowers.borrowernumber, YEAR(dateofbirth), 2, NOW(), NOW() FROM prevu_rbx.borrowers)";

        //-----------------------------------------------------------
        //Import des items
        //-----------------------------------------------------------

        $sql = "INSERT INTO `item`(`id_book`, `price`, koha, library, `date_creation`, `last_update`) (SELECT p.prevu,price, i.itemnumber,:library, NOW(), NOW() FROM koha.items as i INNER JOIN prevu.association as p ON i.biblionumber = p.koha)";
        //INSERT INTO prevu.item(`id_book`, `price`, library, `date_creation`, `last_update`) (SELECT p.prevu,price, 1, NOW(), NOW() FROM koha.items as i INNER JOIN prevu.key as p ON i.biblionumber = p.koha)
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("library", $library);
        $stmt->execute();


        //Roubais - lent avec Roubaix environ 10 min
        $sql = "INSERT INTO `item`(`id_book`, `price`, koha, library, `date_creation`, `last_update`) (SELECT p.prevu,price, i.itemnumber,2, NOW(), NOW() FROM prevu_rbx.items as i INNER JOIN prevu.association as p ON i.biblionumber = p.koha)";

        //--------------------------------------------
        //---------Import des issues
        //--------------------------------------------

//        $sql = "INSERT INTO `issue`(`id_borrower`, `id_book`, `sex`, `datedue`, `issuedate`, `returndate`, `renewals`, `date_creation`, `last_update`, `id_library`)(SELECT id_borrower, k.prevu, b.sex, i.date_due, i.issuedate, i.returndate, i.renewals ,NOW(), NOW(), 1  FROM old_issues as i INNER JOIN borrowers as b ON i.borrowernumber = b.borrowernumber )";



//        INSERT INTO prevu.issue(`id_borrower`, `id_book`, `sex`, `issuedate`, `returndate`, `renewals`, `date_creation`, `last_update`, `id_library`)(
//    SELECT p.id_borrower,
//    k.prevu,
//    b.sex,
//    i.issuedate,
//    i.returndate,
//    i.renewals,
//    NOW(),
//    NOW(),
//    1
//    FROM koha.old_issues as i
//	INNER JOIN koha.borrowers as b ON i.borrowernumber = b.borrowernumber
//	INNER JOIN prevu.borrower as p ON p.koha = b.borrowernumber
//	INNER JOIN koha.biblioitems as t ON t.biblioitemnumber = i.itemnumber
//	INNER JOIN prevu.key as k ON t.`biblionumber` = k.koha  )


        $sql = "INSERT INTO prevu.issue(`koha_borrower`, `koha_item`, `sex`, `issuedate`, `returndate`, `renewals`, `date_creation`,`last_update`, `id_library`)(SELECT i.borrowernumber, i.itemnumber, b.sex, i.issuedate, i.returndate, i.renewals, NOW(), NOW(),:library FROM koha.old_issues as i LEFT JOIN koha.borrowers as b ON i.borrowernumber = b.borrowernumber)";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("library", $library);
        $stmt->execute();

        //Rbx
        $sql = "INSERT INTO prevu.issue(`koha_borrower`, `koha_item`, `sex`, `issuedate`, `returndate`, `renewals`, `date_creation`,`last_update`, `id_library`)(SELECT i.borrowernumber, i.itemnumber, b.sex, i.issuedate, i.returndate, i.renewals, NOW(), NOW(),:library FROM prevu_rbx.old_issues as i LEFT JOIN koha.borrowers as b ON i.borrowernumber = b.borrowernumber)";


        //Update des id borrowers

        //création d'index
        $sql = "CREATE INDEX index_borrower ON borrower (id_borrower)";
        $sql = "CREATE INDEX index_borrower ON borrower (`koha`)";
        $sql = "CREATE INDEX index_borrower ON issue (id_borrower)";
        $sql = "CREATE INDEX index_kohaborrower ON issue (koha_borrower)";
        $sql = "CREATE INDEX index_kohaitem ON issue (koha_item)";

        //Update des id_books

        $sql = "UPDATE issue i JOIN borrower b ON b.koha = i.koha_borrower SET i.id_borrower= b.id_borrower WHERE i.id_library = 1";
        //Rbx (ok avec index)
        $sql = "UPDATE issue i JOIN borrower b ON b.koha = i.koha_borrower SET i.id_borrower= b.id_borrower WHERE i.id_library = 2";


        //Update des id notices
        $sql = "CREATE INDEX index_item ON item (koha)";
        $sql = "CREATE INDEX index_item ON issue (koha_item)";

        $sql = "SELECT i.id_book FROM issue i LEFT JOIN item t ON t.koha = i.koha_item";
        $sql = "UPDATE issue i JOIN item t ON t.koha = i.koha_item SET i.id_book = t.id_book WHERE i.id_library = 1";
        //rbx - 10h21 à
        $sql = "UPDATE issue i JOIN item t ON t.koha = i.koha_item SET i.id_book = t.id_book WHERE i.id_library = 2";

        //update des prêts pour les issues
        $sql = "SELECT b.title, COUNT(*) as nb FROM book as b INNER JOIN issue as i ON i.id_book = b.id_book GROUP BY b.id_book ORDER BY nb DESC";

        $sql ="UPDATE `book` SET `issues`=  (SELECT COUNT(*) FROM issue WHERE issue.id_book = book.id_book GROUP BY id_book );"; //ajouter where library

        return $this->render('', array('name' => $name));
    }
}