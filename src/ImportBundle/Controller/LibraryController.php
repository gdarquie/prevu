<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LibraryController extends Controller
{
    /**
     * @Route("/import/library/up8", name="import_library_up8")
     */
    public function LibraryUp8Action()
    {
        $connectionParams = array(
            'dbname' => 'koha',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost:8889',
            'driver' => 'pdo_mysql',
        );


        $config = new \Doctrine\DBAL\Configuration();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

//        $sql = "SELECT * FROM koha.biblio INNER JOIN prevu.book ON koha.biblio.biblionumber = prevu.book.id_book  LIMIT ".$count;


        //si le code = le code

        $em = $this->getDoctrine()->getManager();

        $code = "up8";

        $query = $em->createQuery("SELECT l.code FROM AppBundle:Library l WHERE l.code = :string");
        $query->setParameter('string', $code);
        $check = $query->getResult();


        if(count($check) == 0){

            $sql = "INSERT INTO prevu.library(code, name, description, date_creation,last_update) VALUES('up8','SCD Université Paris 8', 'https://www.bu.univ-paris8.fr/', NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $this->render('ImportBundle:Library:index.html.twig', array(
//            'count' => $count,
            ));
        }

        else{

            $this->addFlash('success', 'Les données existent déjà!');

            return $this->redirectToRoute('import');

        }
    }

    // -------------
    //-----Roubaix---
    //----------------

    /**
     * @Route("/import/library/rbx", name="import_library_rbx")
     */
    public function LibraryRbxAction()
    {
        $connectionParams = array(
            'dbname' => 'prevu_rbx',
            'user' => 'root',
            'password' => 'root',
            'host' => 'localhost:8889',
            'driver' => 'pdo_mysql',
        );


        $config = new \Doctrine\DBAL\Configuration();
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);

//        $sql = "SELECT * FROM koha.biblio INNER JOIN prevu.book ON koha.biblio.biblionumber = prevu.book.id_book  LIMIT ".$count;


        //si le code = le code

        $em = $this->getDoctrine()->getManager();

        $code = "rbx";

        $query = $em->createQuery("SELECT l.code FROM AppBundle:Library l WHERE l.code = :string");
        $query->setParameter('string', $code);
        $check = $query->getResult();


        if(count($check) == 0){

            $sql = "INSERT INTO prevu.library(code, name, description, date_creation,last_update) VALUES('rbx','Bibliothèque de Roubaix', '', NOW(), NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            return $this->render('ImportBundle:Library:index.html.twig', array(
//            'count' => $count,
            ));
        }

        else{

            $this->addFlash('success', 'Les données existent déjà!');

            return $this->redirectToRoute('import');

        }
    }

}
