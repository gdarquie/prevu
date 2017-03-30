<?php

namespace AppBundle\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AllController extends Controller
{
    /**
     * @Route("/all", name="all")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        //Tout le top 1000
        $query = $em->createQuery("SELECT a FROM AppBundle:Extall a");
        $issues = $query->getResult();

        //Par type
        $query = $em->createQuery("SELECT a.type as type, a.issues as issues FROM AppBundle:Extall a GROUP BY a.type ORDER BY a.issues DESC");
        $types = $query->getResult();

        //Par type
        $query = $em->createQuery("SELECT a FROM AppBundle:Extall a GROUP BY a.author ORDER BY a.issues DESC");
        $authors = $query->getResult();

        return $this->render('AppBundle:All:all.html.twig', array(
            'issues' => $issues,
            'types' => $types,
            'authors' => $authors
        ));
    }

    /**
     * @Route("/all/{type}", name="all_type")
     */
    public function typeAction($type){

        $em = $this->getDoctrine()->getManager();

        //Tous les prêts par type
        $query= $em->createQuery("Select a FROM AppBundle:Extall a WHERE a.type = :type");
        $query->setParameter('type', $type);
        $issues = $query->getResult();

        //auteurs les plus populaires
        $query= $em->createQuery("Select COUNT(a.author) as nb, SUM(a.issues) as issues, a.author as author FROM AppBundle:Extall a WHERE a.type = :type GROUP BY a.author ORDER BY issues DESC");
        $query->setParameter('type', $type);
        $authors = $query->getResult();


        return $this->render('AppBundle:All:type.html.twig', array(
            'type' => $type,
            'issues' => $issues,
            'authors' => $authors
        ));
    }

}


