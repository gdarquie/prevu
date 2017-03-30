<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BnparisController extends Controller
{
    /**
     * @Route("/paris/bn", name="paris_bn")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        //------ total
        $query = $em->createQuery("SELECT COUNT(s) as nb FROM AppBundle:StatsBNLogs s");
        $total = $query->getSingleResult();

        //------- genre
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.gender as title FROM AppBundle:StatsBNLogs s GROUP BY s.gender");
        $genres = $query->getResult();

        //---- csp
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.csp as title FROM AppBundle:StatsBNLogs s GROUP BY s.csp ORDER BY nb DESC");
        $csp = $query->getResult();

        //------ days
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.day as title FROM AppBundle:StatsBNLogs s GROUP BY s.day ORDER BY nb DESC");
        $days = $query->getResult();

        //------ hours
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.hour as title FROM AppBundle:StatsBNLogs s GROUP BY s.hour");
        $hours = $query->getResult();

        //------ birthday
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.birthday as title FROM AppBundle:StatsBNLogs s GROUP BY s.birthday");
        $birthdays = $query->getResult();

        //------ adresse
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.adresse as title FROM AppBundle:StatsBNLogs s GROUP BY s.adresse");
        $adresses = $query->getResult();

        //------ abonnement
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.abonnement as title FROM AppBundle:StatsBNLogs s GROUP BY s.abonnement");
        $abonnements = $query->getResult();

        //------ total d'abonnés
        $query = $em->createQuery("SELECT COUNT(DISTINCT(s.cb)) as nb FROM AppBundle:StatsBNLogs s");
        $total_cb = $query->getSingleResult();

        //------ top user
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.cb as title FROM AppBundle:StatsBNLogs s GROUP BY s.cb ORDER BY nb DESC")->setMaxResults(10);
        $top_users = $query->getResult();

        //------- top books
        $query = $em->createQuery("SELECT COUNT(s.title)as nb, s.title as title, s.ean as ean, s.author as author FROM AppBundle:StatsBNlogs s GROUP BY s.title ORDER BY nb DESC")->setMaxResults(30);
        $top_books = $query->getResult();

        //------- top books
        $query = $em->createQuery("SELECT COUNT(s.author)as nb, s.author as author FROM AppBundle:StatsBNlogs s GROUP BY s.author ORDER BY nb DESC");
        $top_authors = $query->getResult();

        return $this->render('AppBundle:Bnparis:index.html.twig', array(
            'total' => $total,
            'genres' => $genres,
            'csp' => $csp,
            'days' => $days,
            'hours' => $hours,
            'birthdays' => $birthdays,
            'adresses' => $adresses,
            'abonnements' => $abonnements,
            'total_cb' => $total_cb,
            'top_users' => $top_users,
            'top_books' => $top_books,
            'top_authors' => $top_authors
        ));
    }

    /**
     * @Route("/paris/bn/book/{ean}", name="paris_bn_book")
     */
    public function bookAction($ean){
        return $this->render('AppBundle:Bnparis:book.html.twig', array(

        ));
    }

    /**
     * @Route("/paris/bn/author/{name}", name="paris_bn_author")
     */
    public function authorction($name){
        return $this->render('AppBundle:Bnparis:author.html.twig', array(

        ));
    }


    /**
     * @Route("/paris/bn/genre/{genre}", name="paris_bn_genre")
     */
    public function genreAction($genre){


        $em = $this->getDoctrine()->getManager();

        //------- total
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.gender as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre GROUP BY s.gender");
        $query->setParameter("genre" , $genre);
        $total = $query->getSingleResult();

        //-------- CSP
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.csp as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre GROUP BY s.csp ORDER BY nb DESC");
        $query->setParameter("genre" , $genre);
        $csp = $query->getResult();

        //-------- heures
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.hour as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre GROUP BY s.hour ORDER BY title ASC");
        $query->setParameter("genre" , $genre);
        $hour = $query->getResult();

        //-------- adresse
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.adresse as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre GROUP BY s.adresse ORDER BY nb DESC");
        $query->setParameter("genre" , $genre);
        $adresses = $query->getResult();

        return $this->render('AppBundle:Bnparis:genre.html.twig', array(
            'total' => $total,
            'csp' => $csp,
            'hour' => $hour,
            'adresses' => $adresses
        ));

    }

    /**
     * @Route("/paris/bn/genre/{genre}/csp/{csp}", name="paris_bn_genre_csp")
     */
    public function genreCspAction($genre, $csp){

        $em = $this->getDoctrine()->getManager();

        //------- total
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.gender as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre AND s.csp = :csp GROUP BY s.gender");
        $query->setParameter("genre" , $genre);
        $query->setParameter("csp" , $csp);
        $total = $query->getSingleResult();

        //-------- heures
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.hour as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre AND s.csp = :csp GROUP BY s.hour ORDER BY title ASC");
        $query->setParameter("genre" , $genre);
        $query->setParameter("csp" , $csp);
        $hours = $query->getResult();

        //-------- days
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.day as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre AND s.csp = :csp GROUP BY s.day ORDER BY title ASC");
        $query->setParameter("genre" , $genre);
        $query->setParameter("csp" , $csp);
        $days = $query->getResult();

        //-------- adresse
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.adresse as title FROM AppBundle:StatsBNLogs s  WHERE s.gender = :genre AND s.csp = :csp GROUP BY s.adresse ORDER BY nb DESC");
        $query->setParameter("genre" , $genre);
        $query->setParameter("csp" , $csp);
        $adresses = $query->getResult();

        return $this->render('AppBundle:Bnparis:genre_csp.html.twig', array(
            'total' => $total,
            'hours' => $hours,
            'days' => $days,
            'adresses' => $adresses
        ));
    }

}
