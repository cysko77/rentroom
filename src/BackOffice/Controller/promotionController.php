<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;

class promotionController extends Controller
{

    public function afficher($arg = array(), $message = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "code_promo" => "asc", "reduction" => "asc");
        $promotion = $this->getRepository('promotion');
        $tri = $this->triAffichage($arg, $triAffichage);
        $filtre = array();
        //On récupère les données
        $reponse = $promotion->findPromotionBy($filtre, $tri);
        $result = "";
        foreach ($reponse as $k => $value)
        {
            ($k % 2 === 0) ? $bg = "#f8f8f8" : $bg = "#fff";
            $result .= '<tr style="background:' . $bg . '">';
            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    $result .= "<td>$val</td>";
                }
            }
            $result .= '<td><a href="index.php?controller=promotion&action=modifier&id=' . $value->id . '" style="color:orange"><span class="glyphicon glyphicon-remove"></span></a></td>';
            $result .= '<td><a class="delete" data="de supprimer une promotion" href="index.php?controller=promotion&action=supprimer&id=' . $value->id . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $result .= "</tr>";
        }

        $nbrePromotion = count($promotion->findPromotionBy(array()));
        $url = "index.php?controller=promotion&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbrePromotion,$url);

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
            $this->render("promotion/afficher.php", array(
                "title" => "Tous les promotion",
                "promotion" => $result,
                "alert" => $alert,
                "triAffichage" => $triAffichage,
                "pagination" => $pagination
            ));
        }
    }

    public function ajouter()
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $dataReponse = $alert = array();
        if ($request->getMethod() === "POST")
        {
            $dataPost = $request->getParameters("POST");
            $dataPlus = array();
            $dataReponse = $request->checkParameters("promotion", $dataPost, $dataPlus);
            if (!in_array(false, $dataReponse, true))
            {

                // On récupère la reponse
                $promotion = $this->getRepository("promotion");
                $reponse = $promotion->savePromotion($dataReponse);
                if ($reponse === true)
                {
                    $alert = array("success", "La promotion a été créée");
                    $dataReponse = array();
                }
                elseif ($reponse === "notUnique")
                {
                    $alert = array("danger", "Le code promotionnel existe déjà!");
                    $dataReponse['code_promo'] = false;
                }
                else
                {
                    $alert = array("danger", "La promotion n'a pas été ajoutée");
                }
                
                $request->redirection("promotion","afficher",$alert);
                exit();
            }
            else
            {
                $alert = array("danger", "Le promotion n'a pas été enregistrée! Vérifiez votre formulaire!");
            }
        }


        // On affiche les resultats
        $this->render("promotion/ajouter.php", array(
            "title" => "Ajouter une promotion",
            "dataReponse" => $dataReponse,
            "alert" => $alert
        ));
    }

    public function supprimer($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $promotion = $this->getRepository('promotion');
        $id = (!empty($arg)) ? $arg[0] : null;
        if ($id !== null && $id !== 0)
        {
            if ($this->haveProduct($id))
            {
                $alert = array("danger", "La promotion ne peut pas être supprimée car elle est rattachée à un ou plusieurs produits");
            }
            else
            {
                $alert = ($promotion->supprimerPromotion($id)) ? array("success", "La promotion a été supprimée") : array("danger", "La promotion n'a pas été supprimée car elle n'existe pas! ");
            }
        }
        else
        {
            $alert = array("danger", "La promotion n'a pas été supprimée car elle n'existe pas!");
        }
        $request->redirection("promotion","afficher",$alert);
        exit();
    }

    public function haveProduct($id)
    {
        $produit = $this->getRepository("promotion");
        $filtre = array("p.promotion_id = '$id'", "date_depart > NOW()");
        $produits = $produit->getProduit($filtre);
        return $produits ? true : false;
    }

    public function modifier($arg = array())
    {

        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $promotion = $this->getRepository('promotion');
        (!empty($arg)) ? $id = $arg[0] : $id = false;
        settype($id, "integer");
        if ($request->getMethod() == "GET")
        {

            // Si l'id est correctement renseigné dans l'url au affiche les données dans un formulaire 
            if ($id)
            {
                $filtre = array("id = $id");
                $dataReponse = $promotion->findPromotionBy($filtre);
                if ($dataReponse)
                {
                    $alert = array();
                }
                else
                {
                    $alert = array("danger", "La promotion que vous voulez modifier n'existe pas!");
                    $this->afficher(array(), $alert);
                    exit();
                }
            }
            else
            {
                // Redirection vers les produits
                $dataReponse = array('promotion' => false,);
                $alert = array("danger", "La promotion que vous voulez modifier n'existe pas!");
                $this->afficher(array(), $alert);
                exit();
            }
        }
        if ($request->getMethod() === "POST")
        {
            $dataPost = $request->getParameters("POST");
            $dataPlus = array("id" => "$id");
            $dataReponse = $request->checkParameters("promotion", $dataPost, $dataPlus);
            if (!in_array(false, $dataReponse, true))
            {

                // On récupère la reponse
                $promotion = $this->getRepository("promotion");
                $dataSave = $dataReponse + $dataPlus;
                $reponse = $promotion->savePromotion($dataSave);
                if ($reponse === true)
                {
                    $alert = array("success", "La promotion a été modifiée");
                    $dataReponse = array();
                }
                else
                {
                    $alert = array("danger", "La promotion n'a pas été modifiée");
                }
                $request->redirection("promotion","afficher",$alert);
                exit();
            }
            else
            {
                $alert = array("danger", "Le promotion n'a pas été modifiée! Vérifiez votre formulaire!");
            }
        }



        // On affiche les resultats
        $this->render("promotion/modifier.php", array(
            "title" => "Modifier une promotion",
            "dataReponse" => $dataReponse,
            "alert" => $alert
        ));
    }

}
?>
 
