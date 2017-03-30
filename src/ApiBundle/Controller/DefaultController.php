<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



use AppBundle\Entity\Book;

class DefaultController extends Controller
{
    /**
     * @Route("/api/test")
     * @Method("POST")
     */
    public function testAction()
    {
        return new Response('Let\'s do this!');
    }


    //le nombre de prêts par heure selon les jours de la semaine : permet de voir d'un simple coup d'œil les jours où il y a le plus d'emprunt.
    /**
     * @Route("/api/bn/hours")
     */
    public function bnAction(){

        $em = $this->getDoctrine()->getManager();

        //------ days
        $query = $em->createQuery("SELECT COUNT(s) as nb, s.day as day, s.hour as hour FROM AppBundle:StatsBNLogs s GROUP BY s.day, s.hour ORDER BY nb DESC");
        $days = $query->getResult();
        //day, hour, value

//        foreach($days as $item  ){
//            dump($item);
//        }
//
//      die;

        return new JsonResponse($days);
    }

    /**
     * @Route("/api/elastic")
     */
    public function elasticAction(){
        $finder = $this->container->get('fos_elastica.finder.app.user');
//        dump($finder);die();
        $results = $finder->find('roubaix');
//                dump($results);die();
        return new JsonResponse($results[0]->getUsername());
    }
}
