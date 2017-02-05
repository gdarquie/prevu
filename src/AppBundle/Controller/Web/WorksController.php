<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class WorksController extends Controller
{
    /**
     * @Route("/oeuvres", name="oeuvres")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        //Nombres de notices qui ont été empruntées
        $query = $em->createQuery("SELECT COUNT(b.title) as nb FROM AppBundle:Book b WHERE b.issues > 0");
        $nbNoticesEmpruntees = $query->getSingleResult();

        //Nombres de notices avec des œuvres
        $query = $em->createQuery("SELECT COUNT(b.work) as nb FROM AppBundle:Book b WHERE b.work IS NOT NULL");
        $nbNoticesOeuvres = $query->getSingleResult();

        //Nombre de prêts qui ont des œuvres associées
        //???

        //Nombres d'œuvres différentes
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.work)) as nb FROM AppBundle:Book b WHERE b.work IS NOT NULL");
        $nbOeuvres = $query->getSingleResult();

        //Top 100 des œuvres
        $query = $em->createQuery("SELECT b.workTitle as title, b.author as auteur, b.workAuthor as workAuteur, COUNT(b.title) as nb FROM AppBundle:Book b WHERE b.work IS NOT NULL GROUP BY b.workTitle ORDER BY nb DESC")->setMaxResults(100);
        $top100oeuvres= $query->getResult();

        //top œuvres empruntées
        $query = $em->createQuery("SELECT b.workTitle as title,  b.author as auteur, b.work as work, b.workAuthor as workAuteur, SUM(b.issues) as total FROM AppBundle:Book b WHERE b.work IS NOT NULL GROUP BY b.workTitle ORDER BY total DESC")->setMaxResults(100);
        $top100oeuvresPrets= $query->getResult();

        //par année?
        $query = $em->createQuery("SELECT b.workTitle as title,  b.author as auteur, SUM(b.issues) as total FROM AppBundle:Book b WHERE b.work IS NOT NULL GROUP BY b.workTitle ORDER BY total DESC")->setMaxResults(100);
        $noticesOeuvre= $query->getResult();


        //les auteurs les plus empruntés (group by workAuteur)
        $query = $em->createQuery("SELECT b.workTitle as title,  b.workAuthor as auteur, b.work as work, SUM(b.issues) as total FROM AppBundle:Book b WHERE b.work IS NOT NULL AND b.workAuthor IS NOT NULL GROUP BY b.workAuthor ORDER BY total DESC")->setMaxResults(100);
        $top100auteursOeuvres= $query->getResult();

        //Le nombre total de prêts avec des œuvres
        $query = $em->createQuery("SELECT COUNT(b.work) as nb FROM AppBundle:Issue i JOIN i.idbook b WHERE b.work IS NOT null")->setMaxResults(1);
        $totalPretOeuvres= $query->getSingleResult();

        //Le nombre total de prêts du top des auteurs
        $query = $em->createQuery("SELECT COUNT(i.idIssue) as nb FROM AppBundle:Issue i JOIN i.idbook b");
        $totalPret= $query->getSingleResult();

        //nb de renewals
        $query = $em->createQuery("SELECT SUM(b.renewals) as nb FROM AppBundle:Book b");
        $nbRenewals = $query->getSingleResult();

        //les notices du livre le plus emprunté // à reprendre

        //notices d'une œuvre
        $query = $em->createQuery("SELECT b.idBook as id , b.title as title, b.issues as issues FROM AppBundle:Book b WHERE b.work = :work")->setMaxResults(100);
        $query->setParameter('work', 'cb162095466'); //possible de mettre une variable dynamique
        $noticesOeuvre= $query->getResult();

        return $this->render('oeuvre/index.html.twig', array(
            'nbOeuvres' => $nbOeuvres,
            'nbNoticesOeuvres' => $nbNoticesOeuvres,
            'top100oeuvres' => $top100oeuvres,
            'top100oeuvresPrets' => $top100oeuvresPrets,
            'nbNoticesEmpruntees' => $nbNoticesEmpruntees,
            'top100auteursOeuvres' => $top100auteursOeuvres,
            'totalPretOeuvres' => $totalPretOeuvres,
            'totalPret' => $totalPret,
            'noticesOeuvre' => $noticesOeuvre,
            'nbRenewals' => $nbRenewals
        ));

    }

    /**
     * @Route("/oeuvres/{id}", name="oeuvre")
     */
    public function oeuvreAction($id){

        //exemple = cb162095466
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery("SELECT SUM(b.issues) as issues, b.workTitle as workTitle, b.workAuthor as workAuthor FROM AppBundle:PrBook b WHERE b.work = :work")->setMaxResults(1);
        $query->setParameter('work', $id );
        $oeuvre= $query->getSingleResult();

        //Notice d'une œuvre
        //sélectionner d'autres infos
        $query = $em->createQuery("SELECT b.idBook as id , b.title as title, b.author as author, b.issues as issues, b.workTitle as workTitle, b.workAuthor as workAuthor, b.publicationyear as released FROM AppBundle:PrBook b WHERE b.work = :work ORDER by b.issues DESC")->setMaxResults(100);
        $query->setParameter('work', $id );
        $noticesOeuvre= $query->getResult();

        //les informations sur les emprunteurs d'une œuvre
        $query = $em->createQuery("SELECT i, AVG(u.yearofbirth) as year, COUNT(DISTINCT i.idborrower) as total FROM AppBundle:PrIssue i JOIN i.idbook b JOIN i.idborrower u WHERE  b.work = :work");
        $query->setParameter('work', $id );
        $userOeuvre= $query->getResult();

        return $this->render('oeuvre/oeuvre.html.twig', array(
            'noticesOeuvre' => $noticesOeuvre,
            'oeuvre' => $oeuvre,
            'userOeuvre' => $userOeuvre
        ));
    }
}

//ajouter à books work_title