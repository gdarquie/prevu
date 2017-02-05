<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    /**
     * @Route("/import/authors/lib={biblio}", name="import_authors")
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

        $sql_total = "SELECT COUNT(*)as nb FROM biblioitems";
        $nbRows = $conn->fetchAssoc($sql_total); //nb de lignes de biblioitems à parser pour récupérer les données auteurs

        $count = $nbRows; //nb de requêtes par session
        $count = 10;


        for ($i = 0; $i < $count; $i++) {

            $sql = "SELECT id_author as id FROM prevu.author ORDER BY id_author DESC LIMIT 1"; //0.0002s
            $lastAuhtor = $conn->fetchAssoc($sql);
            $last_id = $lastAuhtor['id'];

            if ($last_id < 1) {
                $last_id = 0;
            }

            $last_id = 2;
            //Extraction des mains authors

            $sql_author = "SELECT EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"a\"]') as lastname, EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"b\"]') as firstname,  EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"f\"]') as dates FROM biblioitems WHERE biblionumber > " . $last_id . " LIMIT 1;";
            $author = $conn->fetchAssoc($sql_author);


            //INSERT des authors

            //si l'auteur n'est pas déjà dans la BD, on l'ajoute

            $sql = "INSERT INTO `author`(`firstname`, `lastname`, `dates`, `date_creation`, `last_update`) VALUES ('".$author['firstname']."','".$author['lastname']."','".$author['dates']."',NOW(),NOW())";;

            $stmt = $connPrevu->prepare($sql);
            $stmt->execute();

            //Sélection du biblionumber



            $sql = "SELECT biblionumber as id FROM biblio WHERE biblionumber > " . $last_id . " LIMIT 1"; // 0.0002s
            $id_koha = $conn->fetchAssoc($sql);
            $id_koha = $id_koha['id'];

            //S'il n'y pas d'id_koha, c'est parce que nous arrivons à la fin de l'import (il n'y a pas de plus grand biblionumber)
            if ($id_koha == null) {
                $this->addFlash('success', "L'import a déjà été effectué");
                break;
            } else {

                //Informations auteurs

                $sql = "SELECT as firstname, as lastname, as dates FROM biblio WHERE biblionumber = " . $koha_id;
                $author = "";

                //Insertion des auteurs


                //Insertion des relation book-auteur dans books : ajouter l'id de l'auteur dans book

                $sql = "UPDATE `book` SET `id_author`=[value-19]";

            }

        return $this->render('', array('name' => $name));
        }
    }
}
