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

        return $this->render('AppBundle:Library:index.html.twig', array(

        ));
    }
}
