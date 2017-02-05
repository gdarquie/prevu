<?php

namespace ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BorrowerController extends Controller
{
    public function borrowerUp8Action($name)
    {

        //-----------------------------------------------------------
        //Import des borrowers
        //-----------------------------------------------------------

        //vérification pour éviter les doublons borrowers : une clé par bibliothèque

        $sql_borrower = "SELECT MD5(borrowers.borrowernumber) FROM koha.borrowers";
        $sql_borrower = "INSERT INTO `borrower`(`id_borrower`, `yearofbirth`, `date_creation`, `last_update`) VALUES ([value-1],[value-2],[value-3],[value-4])";

        $id_koha = "";

        $id_prevu ="";

        //if id_koha est dans id_prevu : on n'insère pas

        if($check < 1){

            //insert des borrowers

            //de koha.borrowers à prevu.
            //id et yearofbirth

            //insert des clés
            // INSERT INTO prevu.key(koha,prevu,type,library,date_creation, last_update)
        }

        //insert d'un nouveau borrower = insertion dans borrower et insertion de sa key dans key, si sa clé existe déjà, on ne l'insère pas, sinon on l'insère

        $sql_borrower = "INSERT INTO `borrower`(`id_borrower`, `yearofbirth`, `date_creation`, `last_update`) VALUES ([value-1],[value-2],[value-3],[value-4])";


        return $this->render('', array('name' => $name));
    }
}
