<?php

namespace EcranBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Thesaurus;
use AppBundle\Form\ThesaurusType;

/**
 * @Route("/ecran/editeur")
 */
class EditeurController extends Controller
{
    /**
     * @Route("/", name="ecran_editor")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT t FROM AppBundle:Thesaurus t");
        $thesaurus = $query->getResult();

        $query = $em->createQuery("SELECT b FROM AppBundle:Book b JOIN b.keys k WHERE b.title LIKE :string AND k.library = 1");
        $query->setParameter("string", "%écran%");
        $books = $query->getResult();

        return $this->render('EcranBundle:Editeur:index.html.twig', array(
            'thesaurus' => $thesaurus,
            'books' => $books
        ));
    }


    //create a new thesaurus item


    /**
     * @Route("/creer", name="ecran_editor_add")
     */
    public function addThesaurusEditorAction(Request $request){

        $thesaurus = new Thesaurus();

        $form = $this->createForm(ThesaurusType::class, $thesaurus);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $now = new \DateTime();
            $thesaurus->setLastUpdate($now);
            $thesaurus->setDateCreation($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($thesaurus);
            $em->flush();

            return $this->redirectToRoute('ecran_editor');
        }

        return $this->render('EcranBundle:Editeur:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    //associate an item to a book

    /**
     * @Route("/lier", name="ecran_editor_associate")
     */
    public function addBookAction()
    {
        return $this->render('EcranBundle:Editeur:lier.html.twig', array(

        ));
    }

}
