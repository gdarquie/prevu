<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        //if user est connecté en tant qu'user

        if ($this->getUser()){

            $user = $this->getUser();

            if ($user->hasRole('ROLE_USER')){


                $em = $this->getDoctrine()->getManager();

                $query = $em->createQuery("SELECT COUNT(b.title) as nb FROM AppBundle:Book b WHERE b.issues > 0");
                //additionner les renewals
                $nbNoticesEmpruntees = $query->getSingleResult();

                //nb de prêts par PrIssues
                $query = $em->createQuery("SELECT COUNT(i.idIssue) as nb FROM AppBundle:Issue i");
                $nbNoticesIssues = $query->getSingleResult();

                //nb de prêts par PrBooks
                $query = $em->createQuery("SELECT SUM(b.issues) as nb FROM AppBundle:Book b");
                $nbNoticesIssues2 = $query->getSingleResult();

                //nb d'emprunteurs
                $query = $em->createQuery("SELECT COUNT(b.idBorrower) as nb FROM AppBundle:Borrower b");
                $nbNoticesBorrowers = $query->getSingleResult();

                //nb de renewals
                $query = $em->createQuery("SELECT SUM(b.renewals) as nb FROM AppBundle:Book b");
                $nbRenewals = $query->getSingleResult();

                //date premier emprunt
                $query = $em->createQuery("SELECT i.returndate as nb FROM AppBundle:Issue i ORDER BY i.returndate ASC")->setMaxResults(1);
                $firstEmprunt = $query->getSingleResult();

                //date dernier emprunt
                $query = $em->createQuery("SELECT i.returndate as nb FROM AppBundle:Issue i ORDER BY i.returndate DESC")->setMaxResults(1);
                $lastEmprunt = $query->getSingleResult();

                return $this->render('index.html.twig', array(
                    'nbNoticesEmpruntees' => $nbNoticesEmpruntees,
                    'nbNoticesIssues' => $nbNoticesIssues,
                    'nbNoticesIssues2' => $nbNoticesIssues2,
                    'nbNoticesBorrowers' =>$nbNoticesBorrowers,
                    'nbRenewals' => $nbRenewals,
                    'firstEmprunt' => $firstEmprunt,
                    'lastEmprunt' => $lastEmprunt
                ));
            }

            else {
                return new Response(
                    '<html><body><p>Mais qui êtes-vous?</p></body></html>'
                );
            }

        }

        //sinon une page très simple
        else{
            return $this->render('renvoi.html.twig');
        }
    }

    /**
     * @Route("/news", name="news")
     */
    public function newsAction()
    {
        return $this->render('news/index.html.twig');
    }

    /**
     * @Route("/contribuer", name="contribuer")
     */
    public function contributeAction()
    {
        return $this->render('AppBundle:Contribuer:index.html.twig');
    }

    /**
     * @Route("/elastic/{string}")
     */
    public function elasticAction($string){
        $finder = $this->container->get('fos_elastica.finder.app.book');
//        dump($finder);die();
        $results = $finder->find($string);
//                dump($results);die();

        return $this->render('AppBundle:Elastic:index.html.twig', array(
            'results' => $results,
            'string' => $string
        ));
    }


}
