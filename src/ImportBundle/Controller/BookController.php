<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BookController extends Controller
{

    //---------------------------
    //----Import all books ------
    //---------------------------

    /**
     * @Route("/import/books/lib={biblio}", name="import_books")
     */
    public function ImportBooksAction($biblio){

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

            //Sélection du dernier biblionumber qu'on a enregistré dans Prévu

            $sql_koha = "SELECT koha as id FROM `key` WHERE library = :library ORDER BY id_key DESC LIMIT 1"; //0.0002s
            $stmt = $connPrevu->prepare($sql_koha);
            $stmt->bindValue("library", $library);
            $stmt->execute();
            $lastKoha = $stmt->fetch(); //dernier id de koha sauvegardé dans key
            $last_id = $lastKoha['id'];

            //Vérification qu'il ne s'agit pas de la première notice qu'on insère, si c'est le cas, on crée un faux last id à 0

            if ($last_id < 1) {
                $last_id = 0;
            }


            $sql = "SELECT biblionumber as id FROM biblio WHERE biblionumber > :id LIMIT 1"; // 0.0002s
            $stmt = $conn->prepare($sql);
            $stmt->bindValue("id", $last_id);
            $stmt->execute();
            $id_koha = $stmt->fetch();
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
                $sql = "SELECT COUNT(*) as nb FROM prevu.key WHERE prevu = :id AND library = :library ";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue("id", $id_koha);
                $stmt->bindValue("library", $library);
                $stmt->execute();
                $check = $stmt->fetch();
                $check = $check['nb'];

//                $check = 0;

                if ($check < 1) {

                    //-----------------------------------------------------------------------------------------------------------------
                    //Vérification si la notice existe déjà dans la base pour une autre bibiliothèque cette fois
                    //-----------------------------------------------------------------------------------------------------------------

                    //si un livre dans une autre bibliothèque mais dans prevu a un titre, un auteur, une année de publication et un isbn, on récupère sa son id KOHA

                    $sql = "SELECT b.title as title, i.isbn as isbn FROM biblioitems as i INNER JOIN biblio as b ON b.biblionumber = i.biblionumber WHERE b.biblionumber = :id ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue("id", $id_koha);
                    $stmt->execute();
                    $ref = $stmt->fetch();

                    //TODO : vérifier cette histoire d'encodage
                    $title = utf8_encode($ref['title']);
                    $isbn = utf8_encode(addslashes($ref['isbn']));

                    $sql = "SELECT COUNT(*) as nb FROM book as b INNER JOIN `key` as k ON k.prevu = b.id_book WHERE title = :title AND isbn = :isbn AND library != :library AND k.type = 'book' ";
                    $stmt = $connPrevu->prepare($sql);
                    $stmt->bindValue("title", $title);
                    $stmt->bindValue("isbn", $isbn);
                    $stmt->bindValue("library", $library);
                    $stmt->execute();
                    $exist = $stmt->fetch();
                    $exist = $exist['nb'];

//                    $exist = 0;

                    if ($exist < 1) {
                        //-----------------------------------------------------------
                        //Création de la notice
                        //-----------------------------------------------------------

                        switch ($library) {
                            case 1:

                                //Insert des nouveaux auteurs

                                $sql_author = "SELECT EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"a\"]') as lastname, EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"b\"]') as firstname,  EXTRACTVALUE(marcxml,'//datafield[@tag=\"700\"]/subfield[@code=\"f\"]') as dates FROM biblioitems WHERE biblionumber > :id LIMIT 1;";
                                $stmt = $conn->prepare($sql_author);
                                $stmt->bindValue("id", $last_id);
                                $stmt->execute();
                                $author = $stmt->fetch();

                                $firstname = utf8_encode(addslashes($author['firstname']));
                                $lastname = utf8_encode(addslashes($author['lastname']));
                                $dates = utf8_encode(addslashes($author['dates']));

                                //on vérifie si l'auteur existe déjà dans Prévu
                                $sql_check = "SELECT COUNT(*) as nb FROM author WHERE firstname = :firstname AND lastname = :lastname AND dates = :dates ";
                                $stmt = $connPrevu->prepare($sql_check);
                                $stmt->bindValue("firstname", $firstname);
                                $stmt->bindValue("lastname", $lastname);
                                $stmt->bindValue("dates", $dates);
                                $stmt->execute();
                                $checkAuthor = $stmt->fetch();
                                $checkAuthor = $checkAuthor['nb'];

                                //Si l'auteur n'existe pas déjà, on insère ses infos
                                if ($checkAuthor < 1) {

                                    $sql = "INSERT INTO author (firstname, lastname, dates) VALUES(:firstname, :lastname, :dates)";
                                    $stmt = $connPrevu->prepare($sql);
                                    $stmt->bindValue('firstname', $firstname);
                                    $stmt->bindValue("lastname", $lastname);
                                    $stmt->bindValue("dates", $dates);
                                    $stmt->execute();

                                    $sql = "SELECT id_author as id FROM author ORDER by id_author DESC LIMIT 1";
                                    $id_author = $connPrevu->fetchAssoc($sql);
                                    $id_author = $id_author['id'];

                                } //Si l'auteur existe, on récupère son id
                                else {
                                    $sql_check = "SELECT id_author as id FROM author WHERE firstname = :firstname AND lastname = :lastname AND dates = :dates LIMIT 1";
                                    $stmt = $connPrevu->prepare($sql_check);
                                    $stmt->bindValue("firstname", $firstname);
                                    $stmt->bindValue("lastname", $lastname);
                                    $stmt->bindValue("dates", $dates);
                                    $stmt->execute();
                                    $id_author = $stmt->fetch();
                                    $id_author = $id_author['id'];
                                }

                                //Insert des notices (attention aux risques d'injection sql

                                $sql_notice = "INSERT INTO prevu.book(id_book, title, author, publicationyear, isbn, date_creation, last_update)(SELECT :prevu, biblio.title, biblio.author, biblioitems.publicationyear, biblioitems.isbn, NOW(), NOW() FROM koha.biblio INNER JOIN koha.biblioitems ON biblio.biblionumber = biblioitems.biblionumber  WHERE biblio.biblionumber > :last LIMIT 1);";
                                break;
                            case 2:
                                $sql_notice = "INSERT INTO prevu.book(id_book, title, author, publicationyear, isbn, date_creation, last_update)(SELECT " . $id_prevu . ", biblio.title, biblio.author, biblioitems.publicationyear, biblioitems.isbn, NOW(), NOW() FROM prevu_rbx.biblio INNER JOIN prevu_rbx.biblioitems ON biblio.biblionumber = biblioitems.biblionumber  WHERE biblio.biblionumber > :last LIMIT 1);";
                                //ajouter les auteurs
                                break;
                        }//end switch

                        //reste CDU, DEWEY : CDU : itemcallnumber d'items
                        $stmt = $conn->prepare($sql_notice);
                        $stmt->bindValue("last", $last_id);
                        $stmt->bindValue("prevu", $id_prevu);
                        $stmt->execute();

                    } //enf if : si la notice n'existe pas, on vient d'ajouter son auteur, la notice et le lien de l'auteur avec la notice

                    //si le livre existait déjà dans une autre biblio, on ajoute aussi un lien avec cette biblio aussi

                    //-----------------------------------------------------------
                    //Ajout de la clé de la notice
                    //-----------------------------------------------------------

                    $sql = "INSERT INTO prevu.key(prevu, koha, type, library, date_creation, last_update) VALUES( :id_prevu, :id_koha,:type, :library , NOW(), NOW() );";


                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue("id_prevu", $id_prevu);
                    $stmt->bindValue("id_koha", $id_koha);
                    $stmt->bindValue("type", 'book');
                    $stmt->bindValue("library", $library);
                    $stmt->execute();

                    $cmp = $i;

                    //-----------------------------------------------------------
                    //Ajout du mains auteur : relation book / author dans book
                    //-----------------------------------------------------------
                    $sql = "UPDATE book SET id_author = :author WHERE id_book = :book";
                    $stmt = $connPrevu->prepare($sql);
                    $stmt->bindValue("author", $id_author);
                    $stmt->bindValue("book", $id_prevu);
                    $stmt->execute();


                    //-----------------------------------------------------------
                    //Ajout des auteurs secondaires : relation book / author many to many dans table intermédiaire - TODO
                    //-----------------------------------------------------------


                    echo "<p>Nombre de livres sauvegardés : " . $cmp . ", time :".time()."</p>"; //A voir

                }//end if : si le biblio n'est pas déjà dans cette biblio - s'il l'est, c'est que l'import est déjà fait pour cette notice

            }//end else du début de l'import après vérification qu'il n'a pas déjà été fait

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

