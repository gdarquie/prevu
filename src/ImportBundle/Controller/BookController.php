<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    //---------------------------
    //----Import all books ------
    //---------------------------

    /**
     * @Route("/import/books/lib={biblio}", name="import_books")
     */
    public function AllBooksUp8Action($biblio){

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

        $sql_total = "SELECT COUNT(*)as nb FROM biblio";
        $totalBooks = $conn->fetchAssoc($sql_total); //nb total de livres dans la library d'origine

        $count = $totalBooks; //nb de requêtes par session

        for ($i = 0; $i< $count ;$i++) {

            //-----------------------------------------------------------
            //Vérification des doublons pour cette bibliothèque
            //-----------------------------------------------------------

            //Sélection du biblionumber

            $sql_koha = "SELECT koha as id FROM prevu.key WHERE library =" . $library . " ORDER BY id_key DESC LIMIT 1"; //0.0002s
            $lastKoha = $conn->fetchAssoc($sql_koha); //dernier id de koha sauvegardé dans key
            $last_id = $lastKoha['id'];

            if ($last_id < 1) {
                $last_id = 0;
            }

            $sql = "SELECT biblionumber as id FROM biblio WHERE biblionumber > " . $last_id . " LIMIT 1"; // 0.0002s
            $id_koha = $conn->fetchAssoc($sql);
            $id_koha = $id_koha['id'];

            //S'il n'y pas d'id_koha, c'est parce que nous arrivons à la fin de l'import (il n'y a pas de plus grand biblionumber)
            if ($id_koha == null) {
                $this->addFlash('success', "L'import a déjà été effectué");
                break;
            }

            else
            {
                //Création de l'id_book pour prévu

                $sql = "SELECT MAX(id_book) as id FROM prevu.book";
                $id_prevu = $conn->fetchAssoc($sql);
                $id_prevu = $id_prevu['id'] + 1;

                //-----------------------------------------------------------
                //Vérification pour voir si on a déjà entré cette donnée
                //-----------------------------------------------------------

                //On compte le nombre de notices qui ont déjà ce code pour savoir si nous possédons déjà cette notice
                $sql = "SELECT COUNT(*) as nb FROM prevu.key WHERE prevu = " . $id_koha . " AND library =" . $library;
                $check = $conn->fetchAssoc($sql);
                $check = $check['nb'];

                if ($check < 1) {

                    //-----------------------------------------------------------------------------------------------------------------
                    //Vérification si la notice existe déjà dans la base pour une autre bibiliothèque cette fois
                    //-----------------------------------------------------------------------------------------------------------------

//                    //si un livre dans une autre bibliothèque mais dans prevu a un titre, un auteur, une année de publication et un isbn, on récupère sa son id KOHA
//

//                    $sql = "SELECT title, author, isbn FROM biblio WHERE biblionumber =".$id_koha;
//                    $exist = $conn->fetchAssoc($sql);
//
//                    dump($exist);die();
//
//                    $sql = "SELECT COUNT(*) as nb FROM prevu.book WHERE title = " . $id_koha . " AND  author = ".." AND isbn = ".." AND library !=" . $library;
//                    $exist = $conn->fetchAssoc($sql);
//                    $exist = $exist['nb'];

                    $exist = 0;

                    if ($exist > 1) {

                        dump('Existe déjà donc on ne sauvegarde que les clés');
                        die();

//                        $sql_code = "INSERT INTO prevu.key(prevu, koha, type, library, date_creation, last_update) VALUES(".$id_prevu.", ".$id_koha.",'book',".$library.", NOW(), NOW() );";
//                        $sql = $sql_code;
//                        $stmt = $conn->prepare($sql);
//                        $stmt->execute();

                    }

                    else {

                        //-----------------------------------------------------------
                        //Création de la notice
                        //-----------------------------------------------------------

                        switch ($library) {
                            case 1:

                                //Insert des nouveaux auteurs

                                $sql_author = "SELECT EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"a\"]') as lastname, EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"b\"]') as firstname,  EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"f\"]') as dates FROM biblioitems WHERE biblionumber > " . $last_id . " LIMIT 1;";
                                $author = $conn->fetchAssoc($sql_author);

                                //on vérifie si l'auteur existe déjà dans Prévu
                                $sql_check = "SELECT COUNT(*) as nb FROM author WHERE firstname = '".utf8_encode(addslashes($author['firstname']))."' AND lastname ='".utf8_encode(addslashes($author['lastname']))."' AND dates = '".utf8_encode(addslashes($author['dates']))."'";
                                $checkAuthor = $connPrevu->fetchAssoc($sql_check);
                                $checkAuthor = $checkAuthor['nb'];

                                if($checkAuthor < 1){
                                //si l'auteur n'est pas déjà dans la BD, on l'ajoute
                                $sql = "INSERT INTO `author`(`firstname`, `lastname`, `dates`, `date_creation`, `last_update`) VALUES ('".(addslashes($author['firstname']))."','".(addslashes($author['lastname']))."','".utf8_encode(addslashes($author['dates']))."',NOW(),NOW())";;

                                $stmt = $connPrevu->prepare($sql);
                                $stmt->execute();
                                }

                                //Insert des seconds auteurs

                                //????

                                //Insert des notices

                                $sql_notice = "INSERT INTO prevu.book(id_book, title, author, publicationyear, isbn, date_creation, last_update)(SELECT " . $id_prevu . ", biblio.title, biblio.author, biblioitems.publicationyear, biblioitems.isbn, NOW(), NOW() FROM koha.biblio INNER JOIN koha.biblioitems ON biblio.biblionumber = biblioitems.biblionumber  WHERE biblio.biblionumber > " . $last_id . " LIMIT 1);";
                                break;
                            case 2:
                                $sql_notice = "INSERT INTO prevu.book(id_book, title, author, publicationyear, isbn, date_creation, last_update)(SELECT " . $id_prevu . ", biblio.title, biblio.author, biblioitems.publicationyear, biblioitems.isbn, NOW(), NOW() FROM prevu_rbx.biblio INNER JOIN prevu_rbx.biblioitems ON biblio.biblionumber = biblioitems.biblionumber  WHERE biblio.biblionumber > " . $last_id . " LIMIT 1);";
                                //ajouter les auteurs
                                break;
                        }
                        //reste CDU, DEWEY : CDU : itemcallnumber d'items

                        $sql = $sql_notice;
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        //-----------------------------------------------------------
                        //Ajout de sa clé
                        //-----------------------------------------------------------

                        $sql_code = "INSERT INTO prevu.key(prevu, koha, type, library, date_creation, last_update) VALUES(" . $id_prevu . ", " . $id_koha . ",'book'," . $library . ", NOW(), NOW() );";
                        $sql = $sql_code;
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        $cmp = $i;
                        echo "<p>Nombre de livres sauvegardés : " . $cmp . "</p>"; //A voir
                    }

                }

            }

        }//endfor
            return $this->render('ImportBundle:Default:index.html.twig');
    }

    /**
     * @Route("/import/delete/books", name="delete_books")
     */
    public function deleteAllBooks()
    {
        if ($this->getUser()) {

            $roles = $this->getUser()->getRoles();

            $count = count($roles);

            foreach ($roles as $role ){

                if($role == "ROLE_SUPER_ADMIN"){

                    $connectionParams = array(
                        'dbname' => 'prevu',
                        'user' => 'root',
                        'password' => 'root',
                        'host' => 'localhost:8889',
                        'driver' => 'pdo_mysql',
                    );

                    $config = new \Doctrine\DBAL\Configuration();
                    $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

                    $sql_key = "DELETE FROM `key`;";
                    $sql_book = "DELETE FROM `book`;";

                    $sql = $sql_key.$sql_book;
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    $this->addFlash('success', 'Suppression des données');

                    return $this->redirectToRoute('import');
                }

                else{

                    $this->addFlash('success', 'Vous ne pouvez pas supprimer les données');

                    return $this->redirectToRoute('import');
                }
            }

        }

        return $this->render('ImportBundle:Default:index.html.twig');
    }

}

