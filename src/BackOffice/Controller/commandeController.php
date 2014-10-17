<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;

class commandeController extends Controller
{

    public function afficher($arg = array(), $message = array(), $details = null, $id = null)
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "montant" => "asc", "date" => "asc", "statut_commande" => "asc", "membre_id" => "asc");

        // On va chercher les données correspondantes aux trois dernières offres
        $commande = $this->getRepository('commande');
        $tri = $this->triAffichage($arg, $triAffichage);
        if (!$tri)
            $tri = array();
        //On récupère les données
        $commandes = $commande->findCommandeBy(array(), $tri);
        $result = "";
        foreach ($commandes as $k => $value)
        {
            $bg = ($k % 2 === 0) ? "#f8f8f8" : "#fff";
            $result .= '<tr style="background:' . $bg . '">';



            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    if ($key === "date")
                    {
                        $date = new \DateTime($val);
                        $date = date_format($date, "d/m/Y H:i");
                        $result .= "<td id=\"$key-$value->id\" data=\"$date\">$val</td>";
                    }
                    elseif ($key === "montant")
                    {
                        $result .= "<td id=\"$key-$value->id\" data=\"$val\">" . $val . "€</td>";
                    }
                    elseif ($key === "statut_commande")
                    {
                        $result .= "<td><select data=\"$value->id\" disabled id=\"$key-" . $k . "\" class=\"$key\" name=\"$key\" >";
                        $selected = ($val === "En attente de paiement") ? "selected" : "";
                        $result .= "<option value=\"En attente de paiement\" $selected>En attente de paiement</option>";
                        $selected = ($val === "Payée") ? "selected" : "";
                        $result .="<option value=\"Payée\" $selected>Payée</option>";
                        $selected = ($val === "Annulée") ? "selected" : "";
                        $result .="<option value=\"Annulée\" $selected>Annulée</option>";
                        $result .= "</select><span id=\"inputs-$value->id\" class=\"inputs\"></span>&nbsp;&nbsp;<span style=\"cursor:pointer\" class=\"glyphicon glyphicon-lock\"></span>&nbsp;&nbsp;<span style=\"width:15px;height:15px;display:inline-block\"><img style=\"display:none\" id=\"load-$value->id\" src=\"" . WEBROOT . "web/lokisalle/images/77.GIF\" alt=\"loader\"/></span></td>";
                    }
                    elseif ($key === "id")
                    {
                        $result .= "<td id=\"$key-$value->id\" data=\"$val\"><strong><a style=\"color:#000\" href=\"index.php?controller=commande&action=details&id=$val\" >$val</a></strong></td>";
                    }
                    else
                    {
                        $result .= "<td id=\"$key-$value->id\" data=\"$val\">".$this->cleanAntiSlash($val)."</td>";
                    }
                }
            }

            $result .= "</tr>";
        }

        $nbrecCommande = count($commande->findCommandeBy());
        $url = "index.php?controller=commande&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbrecCommande,$url);


        if ($request->isAjax())
        {
            echo json_encode(array("reponse" => "success", "result" => $result, "pagination" => $pagination));
        }
        else
        {
            $ca = $commande->chiffreAffaire();
            $alert = (!empty($message)) ? $message : array();
            $details = (!empty($details)) ? $details : array();
            $id = (!empty($id)) ? $id : array();
            // On affiche les resultats
            $this->render("commande/afficher.php", array(
                "title" => "Tous les commandes",
                "commandes" => $result,
                "alert" => $alert,
                "triAffichage" => $triAffichage,
                "ca" => $ca,
                "details" => $details,
                "id" => $id,
                "pagination" => $pagination
            ));
        }
    }

    /*
     * Permet de modifier le statut d'une commande.
     *
     */

    public function modifier()
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $dataPost = $request->getParameters("POST");
        (!empty($dataPost['id'])) ? $id = $dataPost['id'] : $id = false;
        settype($id, "integer");
        if ($id)
        {
            // Si le formulaire est soumis
            if ($request->getMethod() == "POST")
            {
                $commande = $this->getRepository('commande');
                $dataPlus = array("id" => $id);
                $dataPost['statut_commande'] = html_entity_decode($dataPost['statut_commande'], ENT_QUOTES, "UTF-8");
                unset($dataPost['id']);
                $dataReponse = $request->checkParameters("commande", $dataPost, $dataPlus);
                // On vérifie s'il ya des erreurs dans les données envoyées
                if (!in_array(false, $dataReponse, true))
                {
                    // On tente de sauvegarde  les données.
                    $dataSave = $dataReponse + $dataPlus;
                    // On récupère la reponse 
                    $reponse = $commande->saveCommande($dataSave);

                    $alert = (isset($reponse['success'])) ? array("success", $reponse['success']) : array("danger", $reponse['danger']);
                    if ($reponse['reponse'])
                    {
                        // On modifie l'etat du produit (0 ou 1)
                        $commande->activeProduit($id, $dataSave['statut_commande']);
                    }
                }
                else
                {
                    $alert = array("danger", "Le statut de la commande n'a pas été modifié!");
                }
            }
        }
        else
        {
            $alert = array("danger", "La commande n'exista pas!");
        }
        echo json_encode(array("alert" => $alert), JSON_FORCE_OBJECT);
    }

    public function details($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $commande = $this->getRepository('commande');
        (!empty($arg)) ? $id = $arg[0] : $id = false;
        settype($id, "integer");
        if ($id)
        {
            $triAffichage = array("id" => "asc", "commande_id" => "asc", "prix" => "asc", "date" => "asc", "membre_id" => "asc", "pseudo" => "asc", "produit_id" => "asc", "titre" => "asc", "salle_id" => "asc", "nom_reel" => "asc");

            //On récupère les données
            $details = $commande->getDetailsCommande($id);
            $result = "";
            foreach ($details as $k => $value)
            {
                ($k % 2 === 0) ? $bg = "#f8f8f8" : $bg = "#fff";
                $result .= '<tr style="background:' . $bg . '">';

                foreach ($value as $key => $val)
                {
                    if (array_key_exists($key, $triAffichage))
                    {
                        if ($key === "prix")
                        {
                            $result .= "<td>" . $val . "€</td>";
                        }
                        else
                        {
                            if ($val === null)
                                $val = "-";
                            $result .= "<td>$val</td>";
                        }
                    }
                }
            }
            $this->afficher(array(), array(), $result, $id);
        }
        else
        {
            $alert = array("danger", "Le détails de cette commande n'existe pas!");
            $this->afficher(array(), $alert);
        }
    }

}

?>