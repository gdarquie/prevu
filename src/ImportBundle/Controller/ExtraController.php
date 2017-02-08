<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExtraController extends Controller
{

    /**
     * @Route("/import/extra/lib={biblio}", name="import_extra")
     */
    public function indexAction($biblio)
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


        //Import des ccodes

        $sql = "INSERT INTO prevu.code(`code`) SELECT distinct ccode FROM koha.items";
        $stmt = $connPrevu->prepare($sql);
        $stmt->execute();

        //Import des types

        //check

        $sql ="INSERT INTO prevu.itemtype(`itemtype`) SELECT distinct itype FROM koha.items"; //WHERE NOT EXIST
        $stmt = $connPrevu->prepare($sql);
        $stmt->execute();


        //Import des UFR

//        $sql = "INSERT INTO `pr_ufr`(`ufr`) SELECT distinct attribute FROM `borrower_attributes` WHERE code ='UFR';";
//        $stmt = $connPrevu->prepare($sql);
//        $stmt->execute();

        //Import des étapes ???? (créer une entité)

//        $sql = "INSERT INTO `pr_etape`(`etape`) SELECT distinct attribute FROM `ko_borrower_attributes` WHERE code ='Etape';";


        //Import des country

//        $sql = "INSERT INTO prevu.country('country') SELECT DISTINCT (ExtractValue(marcxml,'//datafield[@tag=\"102\"]/subfield[@code=\"a\"]')) AS 'pays' from koha.biblioitems;";
//        $stmt = $conn->prepare($sql);
//        $stmt->execute();


        //Import des languages

//        $sql = "select distinct (ExtractValue(marc,'//datafield[@tag=\"101\"]/subfield[@code=\"a\"]')) AS 'langue' from marcxml;";

        //Import des éditeurs?

        $sql = "";



        $this->addFlash('success', 'Ajout des données extra');

        return $this->redirectToRoute('import');
    }
}

