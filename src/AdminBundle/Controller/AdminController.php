<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/admin")
     */
    public function indexAction()
    {

        //nb de livres où la date de publication est vide
//        $query = $em->createQuery("SELECT b FROM AppBundle:Book");
//        $query->setParameter('string', '%'.$string.'%');
//        $docByDecade = $query->getResult();

        return $this->render('AdminBundle:Default:index.html.twig', array(

        ));
    }
}
