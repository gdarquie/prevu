<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{

    //add a book
//    /**
//     * @Route("/api/books", name="api_book_add")
//     * @Method("POST")
//     */
    public function newAction(Request $request)
    {

        $data = json_decode($request->getContent(), true);

        $book = new Book();
//        $form = $this->createForm(new BookType(), $book); //pb avec la création du form
//        dump($book);die();
//        $form->submit($data);

//        $book->setTitle($data['title']);
//        $book->setAuthor($data['author']);

        $now = new \DateTime();
        $book->setDateCreation($now);
        $book->setLastUpdate($now);

        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();

        return new Response('It worked. Believe me - I\'m an API');

        return new Response($data);
    }

    //show a book
    /**
     * @Route("/api/book/{title}", name="api_book_show")
     * @Method("GET")
     */
    public function showAction($title)
    {

        $book = $this->getDoctrine()
            ->getRepository('AppBundle:Book')
            ->findOneByTitle($title);

        if (!$book) {
            throw $this->createNotFoundException(sprintf("Il n'existe pas de livre dont le titre est \"%s\"", $title));
        }

        $data = array(
            'title' => $book->getTitle(),
            'author' => $book->getAuthor()
        );


        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
