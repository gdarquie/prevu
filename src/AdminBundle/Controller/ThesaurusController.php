<?php

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\Thesaurus;
use AppBundle\Form\ThesaurusType;


class ThesaurusController extends Controller
{

    /**
     * @Route("/editeur/thesaurus/add", name="add_thesaurus")
     */
    public function newAction(Request $request){

        $thesaurus  = new Thesaurus();

        $form = $this->createForm(ThesaurusType::class, $thesaurus);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){

            $now = new \DateTime();
            $thesaurus->setDateCreation($now);
            $thesaurus->setLastUpdate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($thesaurus);
            $em->flush();

            return $this->redirectToRoute('ecran_editor');
        }

        return $this->render('AdminBundle:Thesaurus:new.html.twig', array(
            'thesaurusForm' => $form->createView()
        ));
    }

    /**
     * @Route("/editeur/thesaurus/{id}/edit", name="edit_thesaurus")
     */
    public function editAction(Request $request, Thesaurus $thesaurus){

        $deleteForm = $this->createDeleteForm($thesaurus);
        $editForm = $this->createForm('AppBundle\Form\ThesaurusType', $thesaurus);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $now = new \DateTime();
            $thesaurus->setLastUpdate($now);

            $em->persist($thesaurus);
            $em->flush();

            return $this->redirectToRoute('ecran_editor');

        }

        return $this->render('AdminBundle:Thesaurus:edit.html.twig', array(
            'thesaurus' => $thesaurus,
            'thesaurusForm' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

    }

    /**
     * Effacer un thesaurus
     *
     * @Route("editeur/thesaurus/{id}/delete", name="delete_thesaurus")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Thesaurus $thesaurus)
    {
        $form = $this->createDeleteForm($thesaurus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($thesaurus);
            $em->flush();
        }

        return $this->redirectToRoute('ecran_editor');
    }

    /**
     * Créer un form pour effacer un thesaurus
     *
     * @param Thesaurus $thesaurus
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm(Thesaurus $thesaurus)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_thesaurus', array('id' => $thesaurus->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

}
