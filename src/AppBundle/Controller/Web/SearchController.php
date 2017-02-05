<?php

namespace AppBundle\Controller\Web;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{

    /**
     * @Route("/recherche/titre/", name="search")
     */
    public function searchTitle(){

        $em = $this->getDoctrine()->getManager();

        return $this->render('/recherche/index.html.twig', array(

        ));
    }

    /**
     * @Route("/recherche/titre/{string}", name="search_title")
     */
    public function searchTitleByString($string){

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT b.idBook as id , b.title as title, b.issues as issues FROM AppBundle:Books b WHERE b.title = :string")->setMaxResults(100);
        $query->setParameter('string', $string);
        $noticesOeuvre= $query->getResult();

        return $this->render('/recherche/titre.html.twig', array(

        ));
    }

}
