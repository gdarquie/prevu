<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatsController extends Controller
{
//    /**
//     * @Route("/api/isbn", name="api_isbn")
//     */
    public function isbnAction(){

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT b.isbn FROM AppBundle:Book b WHERE b.isbn IS NOT NULL AND b.isbn NOT LIKE ?1 AND b.issues > 0 ")->setParameter(1, "%|%");
        $books = $query->getResult();

        $response = new JsonResponse($books, 200);

        return $response;

    }

    //liste des livres empruntés par année
//    /**
//     * @Route("/api/borrowed/", name="api_borrowedByYear")
//     */
    public function borrowedByYearAction(){

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT b.isbn FROM AppBundle:Issue i JOIN i.idbook b WHERE i.returndate BETWEEN '2012-01-01 00:00:00' AND '2012-12-31 23:59:59' GROUP BY b.isbn");
        $books = $query->getResult();

        $response = new JsonResponse($books, 200);

        return $response;

    }

    private function serializeBook(Book $book)
    {
        return array(
            'title' => $book->getTitle(),
            'isbn' => $book->getIsbn(),
        );
    }

}
