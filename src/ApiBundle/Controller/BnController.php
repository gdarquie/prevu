<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BnController extends Controller
{
    /**
     * @Route("/api/bn/hours", name="api_bn_hours")
     */
    public function hoursAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT COUNT(s) as value, s.day_short as day, s.hour as hour FROM AppBundle:StatsBNLogs s GROUP BY s.day, s.hour ORDER BY value DESC");
        $days = $query->getResult();

//        return new Response($days[0]['day'].",".$days[0]['hour'].",".$days[0]['nb']);
        return new JsonResponse($days);

        //Import MySQL

        //UPDATE `stats_bn_logs` SET `day_short`= 1 WHERE day = "lundi";
        //UPDATE `stats_bn_logs` SET `day_short`= 2 WHERE day = "mardi";
        //UPDATE `stats_bn_logs` SET `day_short`= 3 WHERE day = "mercredi";
        //UPDATE `stats_bn_logs` SET `day_short`= 4 WHERE day = "jeudi";
        //UPDATE `stats_bn_logs` SET `day_short`= 5 WHERE day = "vendredi";
        //UPDATE `stats_bn_logs` SET `day_short`= 6 WHERE day = "samedi";
        //UPDATE `stats_bn_logs` SET `day_short`= 7 WHERE day = "dimanche";
    }

    /**
     * @Route("/api/bn/hours/csv", name="api_bn_hours_csv")
     */
    public function hourscsvAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT COUNT(s) as value, s.day_short as day, s.hour as hour FROM AppBundle:StatsBNLogs s GROUP BY s.day, s.hour ORDER BY value DESC");
        $days = $query->getResult();

        $msg = "day,hour,value<br/>";

        foreach ($days as $item){
            if($item['hour'] == 0){
                $item['hour'] = 24;
            }
            $msg .= $item['day'].",".$item['hour'].",".$item['value']."<br/>";
        }

        $response = new Response($msg);

        $response->setContent($msg);

        return $response;



    }

}


