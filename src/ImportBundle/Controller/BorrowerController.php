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

        $sql = "INSERT INTO borrower(`koha`, `yearofbirth`,`library`, library, `date_creation`, `last_update`) (SELECT MD5(borrowers.borrowernumber), YEAR(dateofbirth), :library, NOW(), NOW() FROM koha.borrowers)"; //les borrowers ont une libary seulement (permet de supprimer
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("library", $library);
        $stmt->execute();


        //-----------------------------------------------------------
        //Import des items
        //-----------------------------------------------------------

        $sql = "INSERT INTO `item`(`id_book`, `price`, library, `date_creation`, `last_update`) (SELECT p.prevu,price, :library, NOW(), NOW() FROM koha.items as i INNER JOIN prevu.key as p ON i.biblionumber = p.koha)";
        //INSERT INTO prevu.item(`id_book`, `price`, library, `date_creation`, `last_update`) (SELECT p.prevu,price, 1, NOW(), NOW() FROM koha.items as i INNER JOIN prevu.key as p ON i.biblionumber = p.koha)
        $stmt = $conn->prepare($sql);
        $stmt->bindValue("library", $library);
        $stmt->execute();


        //--------------------------------------------
        //---------Import des issues
        //--------------------------------------------

        $sql = "INSERT INTO `issue`(`id_borrower`, `id_book`, `sex`, `datedue`, `issuedate`, `returndate`, `renewals`, `date_creation`, `last_update`, `id_library`)(SELECT id_borrower, k.prevu, b.sex, i.date_due, i.issuedate, i.returndate, i.renewals ,NOW(), NOW(), 1  FROM old_issues as i INNER JOIN borrowers as b ON i.borrowernumber = b.borrowernumber )";


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








        $stmt = $conn->prepare($sql);
        $stmt->bindValue("library", $library);
        $stmt->execute();



        return $this->render('', array('name' => $name));
    }
}
