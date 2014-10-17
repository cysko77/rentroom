<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;

class avisController extends Controller
{

    public function afficher($arg = array(), $message = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "note" => "asc", "date" => "asc", "commentaire" => "asc", "salle_id" => "asc", "membre_id" => "asc");
        $avis = $this->getRepository('avis');
        $tri = $this->triAffichage($arg, $triAffichage);
        $filtre = array();
        //On récupère les données
        $reponse = $avis->findAvis($filtre, $tri);
        $avis->filtreText($reponse, "commentaire", 55);
        $result = "";
        foreach ($reponse as $k => $value)
        {
            $bg = ($k % 2 === 0) ? "#f8f8f8" : "#fff";
            $result .= '<tr style="background:' . $bg . '">';
            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    $result .= "<td>".$this->cleanAntiSlash($val)."</td>";
                }
            }
            $result .= '<td><a class="delete" data="de supprimer un avis" href="index.php?controller=avis&action=supprimer&id=' . $value->id . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $result .= "</tr>";
        }

        $nbreAvis = count($avis->findAvis(array()));
        $url = "index.php?controller=avis&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbreAvis,$url);

        if ($request->isAjax())
        {
            echo json_encode(array("reponse" => "success", "result" => $result, "pagination" => $pagination));
        }
        else
        {
            if (isset($_SESSION["message"]) && !empty($_SESSION["message"]))
            {
                $request->getParameters("SESSION");
                $mes = $_SESSION["message"];
                unset($_SESSION["message"]);
                $alert = $mes;
            }
            if(!empty($message))
            {
                $alert = $message;
            }
            // On affiche les resultats
            $this->render("avis/afficher.php", array(
                "title" => "Tous les avis",
                "avis" => $result,
                "alert" => $alert,
                "triAffichage" => $triAffichage,
                "pagination" => $pagination
            ));
        }
    }

    public function supprimer($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $avis = $this->getRepository('avis');
        (!empty($arg)) ? $id = $arg[0] : $id = null;
        if ($id !== null && $id !== 0)
        {
            $alert = $avis->supprimerAvis($arg[0]);
        }
        else
        {
            $alert = array("danger", "L'avis n'a pas été supprimé car il n'existe pas! \n");
        }
        $request->redirection("avis","afficher",$alert);
        exit();
    }

}
?>
 
