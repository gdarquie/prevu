<?php

namespace EcranBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class EcranController extends Controller
{
    /**
     * @Route("/ecran", name="ecran")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.idBook) as nb FROM AppBundle:Book b");
        //additionner les renewals
        $nbNotices = $query->getSingleResult();

        $query = $em->createQuery("SELECT COUNT(b.title) as nb FROM AppBundle:Book b WHERE b.issues > 0");
        //additionner les renewals
        $nbNoticesEmpruntees = $query->getSingleResult();

        $query = $em->createQuery("SELECT MAX(i.returndate) as last, MIN(i.issuedate) as first FROM AppBundle:Issue i");
        //additionner les renewals
        $dateBorrow = $query->getSingleResult();

        $query = $em->createQuery("SELECT COUNT(DISTINCT(i.idIssue)) as nb FROM AppBundle:Issue i");
        //additionner les renewals
        $nbIssues= $query->getSingleResult();

        //nb de prêts par type
        $query = $em->createQuery("SELECT i.itemtype as type, COUNT(i.idItemtype) as nb FROM AppBundle:Book b JOIN b.iditemtype i GROUP BY i.idItemtype ORDER BY nb DESC");
        //additionner les renewals
        $loanByType = $query->getResult();

        //Recherches sur mot écran
        $string = "écran";

        //nb de documents comportant le mot écran dans le titre
        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.idBook)) as nb FROM AppBundle:Book b WHERE b.title LIKE :string");
        $query->setParameter('string', '%'.$string.'%');
        $nbDocumentsEcran = $query->getSingleResult();

        $query = $em->createQuery("SELECT b.title as title, b.author as authors, b.publicationyear as released FROM AppBundle:Book b WHERE b.title LIKE :string");
        $query->setParameter('string', '%'.$string.'%');
        $docEcrans = $query->getResult();

        //nb de documents par années
        $query = $em->createQuery("SELECT b.publicationyear as publicationyear, COUNT(b.idBook) as nb FROM AppBundle:Book b WHERE b.publicationyear > 0 AND b.publicationyear < 2017 AND  b.title LIKE :string GROUP BY b.publicationyear ");
        $query->setParameter('string', '%'.$string.'%');
        $docByYear = $query->getResult();

        //nb de documents par décennie
        $query = $em->createQuery("SELECT FLOOR(b.publicationyear/10)*10 as decade, COUNT(b.idBook) as nb FROM AppBundle:Book b WHERE b.publicationyear > 0 AND b.publicationyear < 2017 AND  b.title LIKE :string GROUP BY decade ");
        $query->setParameter('string', '%'.$string.'%');
        $docByDecade = $query->getResult();


        //nbd de documents par décennie

        return $this->render('EcranBundle:Article:index.html.twig', array(
            'nbNotices' => $nbNotices,
            'nbNoticesEmpruntees' => $nbNoticesEmpruntees,
            'nbIssues' => $nbIssues,
            'dateBorrow' => $dateBorrow,
            'loanByType' => $loanByType,
            'nbDocumentsEcran' => $nbDocumentsEcran,
            'docEcrans' => $docEcrans,
            'docByYear' => $docByYear,
            'docByDecade' => $docByDecade
        ));
    }

    /**
     * @Route("/ecran/prets", name="ecran_prêts")
     */
    public function pretAction(){

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT b, a FROM AppBundle:Book b JOIN b.first_author a WHERE b.title LIKE :string ORDER BY b.issues DESC");
        $query->setParameter('string', '%ecran%')->setMaxResults(1000);
        $notices = $query->getResult();

        $query = $em->createQuery("SELECT a FROM AppBundle:Author a.firstname as firstname
//, b.issue as issues JOIN a.books b WHERE b.title LIKE :string");
//        $query->setParameter('string', '%ecran%')->setMaxResults(1000);
//        $authors = $query->getResult();

        return $this->render('EcranBundle:Article:pret.html.twig', array(
            'notices' => $notices
//            'authors' => $authors
        ));
    }
}
