<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;

class BookController extends Controller
{

    /**
     * @Route("/editeur/book/add", name="add_book")
     */
    public function newAction(Request $request){

        $book  = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){

            $now = new \DateTime();
            $book->setDateCreation($now);
            $book->setLastUpdate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->render('AdminBundle:Book:new.html.twig', array(
            'book' => $book,
            'bookForm' => $form->createView()
        ));
    }

    /**
     * @Route("/editeur/book/{bookId}/edit/", name="edit_book")
     */
    public function editAction(Request $request, $bookId){

        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('AppBundle:Book')->findOneById($bookId);

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $now = new \DateTime();
            $book->setLastUpdate($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
        }

        return $this->render('AdminBundle:Book:edit.html.twig', array(
            'book' => $book,
            'bookForm' => $form->createView()
        ));

    }
}
