<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/import", name="import")
     */
    public function indexAction()
    {
        $config = new \Doctrine\DBAL\Configuration();

        $user = $this->container->getParameter('database_user');
        $password = $this->container->getParameter('database_password');
        $host = $this->container->getParameter('database_host');

        //à corriger, ne pas laisser tel quel
        $connectionParamsUp8 = array(
            'dbname' => 'koha',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost:8889',
            'driver' => 'pdo_mysql',
        );

        $connectionParamsRbx = array(
            'dbname' => 'prevu_rbx',
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => 'pdo_mysql',
        );

        $connectionParamsPrevu = array(
            'dbname' => 'prevu',
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => 'pdo_mysql',
        );

        $config = new \Doctrine\DBAL\Configuration();

        $connUp8 = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsUp8, $config);
        $connRbx = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsRbx, $config);
        $connPrevu = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsPrevu, $config);

        //Calcul du nombre de livres

        $sql_total = "SELECT COUNT(*)as nb FROM book INNER JOIN `key` ON book.id_book = key.prevu WHERE library = 1";
        $totalBooksUp8 = $connPrevu->fetchAssoc($sql_total); //nb total de livres dans book de Prévu pour cette biblio

        $sql_total = "SELECT COUNT(*)as nb FROM book INNER JOIN `key` ON book.id_book = key.prevu WHERE library = 2";
        $totalBooksRbx = $connPrevu->fetchAssoc($sql_total); //nb total de livres dans book de Prévu pour cette biblio

        $sql_total = "SELECT COUNT(*)as nb FROM book";
        $totalBooks = $connPrevu->fetchAssoc($sql_total); //nb total de livres dans Prévu

        $sql_total = "SELECT COUNT(*)as nb FROM biblio";
        $totalBooksInUp8 = $connUp8->fetchAssoc($sql_total); //nb total de livres dans Prévu

        $sql_total = "SELECT COUNT(*)as nb FROM biblio";
        $totalBooksInRbx = $connRbx->fetchAssoc($sql_total); //nb total de livres dans Prévu

        return $this->render('ImportBundle:Default:index.html.twig' , array(
            'totalBooks' => $totalBooks,
            'totalBooksFromUp8' => $totalBooksUp8,
            'totalBooksFromRbx' => $totalBooksRbx,
            'totalBooksInUp8' => $totalBooksInUp8,
            'totalBooksInRbx' => $totalBooksInRbx
        ));
    }

    /**
     * @Route("/import/stats", name="import_stats")
     */
    public function statsAction(){

        $config = new \Doctrine\DBAL\Configuration();

        $user = $this->container->getParameter('database_user');
        $password = $this->container->getParameter('database_password');
        $host = $this->container->getParameter('database_host');

        //à corriger, ne pas laisser tel quel
        $connectionParamsUp8 = array(
            'dbname' => 'koha',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost:8889',
            'driver' => 'pdo_mysql',
        );

        $connectionParamsRbx = array(
            'dbname' => 'prevu_rbx',
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => 'pdo_mysql',
        );

        $connectionParamsPrevu = array(
            'dbname' => 'prevu',
            'user' => $user,
            'password' => $password,
            'host' => $host,
            'driver' => 'pdo_mysql',
        );

        $config = new \Doctrine\DBAL\Configuration();

        $connUp8 = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsUp8, $config);
        $connRbx = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsRbx, $config);
        $connPrevu = \Doctrine\DBAL\DriverManager::getConnection($connectionParamsPrevu, $config);

        //Calcul du nombre de livres

        $sql_total = "SELECT COUNT(*)as nb FROM book INNER JOIN `key` ON book.id_book = key.prevu WHERE library = 1";
        $totalBooksUp8 = $connPrevu->fetchAssoc($sql_total); //nb total de livres dans book de Prévu pour cette biblio

        $sql_total = "SELECT COUNT(*)as nb FROM book INNER JOIN `key` ON book.id_book = key.prevu WHERE library = 2";
        $totalBooksRbx = $connPrevu->fetchAssoc($sql_total); //nb total de livres dans book de Prévu pour cette biblio

        $sql_total = "SELECT COUNT(*)as nb FROM book";
        $totalBooks = $connPrevu->fetchAssoc($sql_total); //nb total de livres dans Prévu

        $sql_total = "SELECT COUNT(*)as nb FROM biblio";
        $totalBooksInUp8 = $connUp8->fetchAssoc($sql_total); //nb total de livres dans Prévu

        $sql_total = "SELECT COUNT(*)as nb FROM biblio";
        $totalBooksInRbx = $connRbx->fetchAssoc($sql_total); //nb total de livres dans Prévu

        return $this->render('ImportBundle:Default:stats.html.twig' , array(
            'totalBooks' => $totalBooks,
            'totalBooksFromUp8' => $totalBooksUp8,
            'totalBooksFromRbx' => $totalBooksRbx,
            'totalBooksInUp8' => $totalBooksInUp8,
            'totalBooksInRbx' => $totalBooksInRbx
        ));
    }


}




// php bin/console doctrine:mapping:import --force ImportBundle yml