<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LibraryController extends Controller
{
    //Home d'une bibliothèque

    /**
     * @Route("/bibliotheque/{id}", name="bibliotheque")
     */
    public function indexAction($id)
    {

        //recupérer la biblio de l'id

        $em = $this->getDoctrine()->getManager();

        $repository = $this->getDoctrine()->getRepository('AppBundle:Library');
        $biblio = $repository->findOneByIdlibrary($id);

        //Nombre de notices d'une biblio
        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.id)) as nb FROM AppBundle:Book b JOIN b.keys k WHERE k.library = :id");
        $query->setParameter('id', $id);
        $nbNotices = $query->getSingleResult();

        //Nombre d'auteurs d'une biblio
        $query = $em->createQuery("SELECT COUNT(DISTINCT(a.idAuthor)) as nb FROM AppBundle:Author a JOIN a.books b JOIN b.keys k WHERE k.library = :id");
        $query->setParameter('id', $id);
        $nbAuteurs = $query->getSingleResult();

        //Nombre de borrower
        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.idBorrower)) as nb  FROM AppBundle:Borrower b WHERE b.library = :id");
        $query->setParameter('id', $id);
        $nbBorrowers = $query->getSingleResult();

        //Nombre d'emprunts
        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.idIssue)) as nb  FROM AppBundle:Issue b WHERE b.idlibrary = :id");
        $query->setParameter('id', $id);
        $nbEmprunts = $query->getSingleResult();

        //Nombre de notices empruntés
        $query = $em->createQuery("SELECT COUNT(DISTINCT(b.idbook)) as nb  FROM AppBundle:Issue b WHERE b.idlibrary = :id");
        $query->setParameter('id', $id);
        $nbNoticesEmpruntes = $query->getSingleResult();

        //Première notice empruntée
        $query = $em->createQuery("SELECT MIN(b.issuedate) as nb FROM AppBundle:Issue b WHERE b.idlibrary = :id");
        $query->setParameter('id', $id);
        $firstEmprunt = $query->getSingleResult();

        //Dernière notice empruntée
        $query = $em->createQuery("SELECT MAX(b.issuedate) as nb FROM AppBundle:Issue b WHERE b.idlibrary = :id");
        $query->setParameter('id', $id);
        $lastEmprunt = $query->getSingleResult();

        //SELECT * FROM association WHERE library = 2 GROUP BY prevu ORDER BY issues DESC LIMIT 10

        //top notices
        $query = $em->createQuery("SELECT p.title as title,  k.issues as issues FROM AppBundle:Key k JOIN k.prevu p WHERE k.library = :id ORDER BY k.issues DESC")->setMaxResults(30);
        $query->setParameter('id', $id);
        $topNotices = $query->getResult();

        //Nb de prêts par data
        $query = $em->createQuery("SELECT i.returndate as returndate, COUNT(i.returndate) as nb FROM AppBundle:Issue i WHERE i.idlibrary = :id GROUP BY i.returndate ORDER BY i.returndate ASC");
        $query->setParameter('id', $id);
        $returns = $query->getResult();


        //top auteurs
        //SELECT les notices qui ont le plus de prêts
        //associer le nombre d'issues aux associations
        $query= $em->createQuery("SELECT FROM AppBundle:Issue i");

        return $this->render('AppBundle:Library:index.html.twig', array(
            "biblio" => $biblio,
            "nbNotices" => $nbNotices,
            "nbAuteurs" => $nbAuteurs,
            "nbBorrowers" => $nbBorrowers,
            "nbEmprunts" => $nbEmprunts,
            "nbNoticesEmpruntes" => $nbNoticesEmpruntes,
            "firstEmprunt" => $firstEmprunt,
            'lastEmprunt' => $lastEmprunt,
            'topNotices' => $topNotices,
            'returns' => $returns
        ));
    }
}
