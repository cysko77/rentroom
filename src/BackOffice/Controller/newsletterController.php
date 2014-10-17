<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;
use SendEmail\SendEmail;

class newsletterController extends Controller
{
    
    
    /*
     * Permet d'afficher la liste des salles.
     * @parameters (array) $arg  && (array) $alert
     *
     */

    public function afficher($arg = array(), $message = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "titre" => "asc", "date_envoi" => "asc", "date_creation" => "asc", "membre_id" => "asc", "pseudo" => "asc", "prenom" => "asc", "nom" => "asc");
        $newsletter = $this->getRepository('newsletter');
        $tri = $this->triAffichage($arg, $triAffichage);
        $newsletters = $newsletter->findNewsletterBy($field = array(), $filtre = array(), $tri);
        $newsletter->filtreText($newsletter, "titre", 17);
        $newsletter->filtreText($newsletter, "contenu", 37);
        $result = "";
        foreach ($newsletters as $k => $value)
        {
            $bg = ($k % 2 === 0) ?  "#f8f8f8" : "#fff";
            $result .= '<tr style="background:' . $bg . '">';
            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    if ($key !== "photo")
                    {
                        $val = (!empty($val)) ? $val : "-";
                        $result .= "<td>".$this->cleanAntiSlash($val)."</td>";
                    }
                    else
                    {
                        $result .= "<td><a class=\"imgSalle link-grey\"  rel=\"lightbox\" title=\"\" href=\"lokisalle/images/salles/miniature/grande/" . $value->photo . "\"><span class=\"glyphicon glyphicon-picture\"></span></a></td>";
                    }
                }
            }
            $result .= '<td><a href="index.php?controller=newsletter&action=envoyer&id=' . $value->id . '" style="color:green"><span class="glyphicon glyphicon-send"></span></a></td>';
            $result .= '<td><a href="index.php?controller=newsletter&action=modifier&id=' . $value->id . '" style="color:orange"><span class="glyphicon glyphicon-remove"></span></a></td>';
            $result .= '<td><a class="delete" data="de supprimer une newsletter" href="index.php?controller=newsletter&action=supprimer&id=' . $value->id . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $result .= "</tr>";
        }

        $nbreNewsletter = count($newsletter->findNewsletterBy($field = array(), $filtre = array()));
        $url = "index.php?controller=newsletter&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbreNewsletter,$url);
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
            $this->render("newsletter/afficher.php", array(
                "title" => "Tous les newsletters",
                "newsletters" => $result,
                "alert" => $alert,
                "triAffichage" => $triAffichage,
                "pagination" => $pagination
            ));
        }
    }
    
    
    
    /*
     * Permet d'ajouter une newsletter
    */

    public function ajouter()
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $newsletter = $this->getRepository('newsletter');
        // Si le formulaire est soumis
        if ($request->getMethod() == "POST")
        {
            $dataSession = $request->getParameters("SESSION");
            $dataPost = $request->getParameters("POST");
            $date = new \DateTime("now");
            $date_creation = $date->format("Y-m-d H:i");
            $dataPlus = array("membre_id" => $dataSession['Auth']->id, "date_creation" => $date_creation, "date_envoi" => null);
            $data = $request->checkParameters("newsletter", $dataPost, $dataPlus);
            $dataReponse = $request->SpecialHTML($data,true);
            // On vérifie s'il ya des erreurs dans les données envoyées
            if (!in_array(false, $dataReponse, true))
            {
                // On tente de sauvegarde  les données.
                $dataSave = $dataReponse + $dataPlus;
                // On récupère la reponse 
                $reponse = $newsletter->creerNewsletter($dataSave);
                $alert = ($reponse) ? array("success","La newsletter a été créée!") : array("danger", "Désolé!, la newsletter n'a pas été créée! Réessayez plus tard");
                $request->redirection("newsletter","afficher",$alert);
                exit();
            }
            else
            {
                $alert = array("danger", "La newsletter n'a pas été créée! \n Vérifiez votre formulaire!");
            }
        }
        else
        {
            //Formulaire non soumis
            $dataReponse = array();
            $alert = array();
        }

        // On affiche les resultats
        $this->render("newsletter/ajouter.php", array(
            "title" => "Créer une newsletter",
            "dataReponse" => $dataReponse,
            "alert" => $alert
        ));
    }

    

    

    /*
     * Permet de modifier une newsletter.
     * @parameters (array) $arg => données de la newsletter
     *
     */

    public function modifier($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $data['id'] = (isset($arg[0])) ?  (int)$arg[0] : false;
        $request->valideParameters($data, "newsletter");
        $newsletter = $this->getRepository("newsletter");

        if ($request->getMethod() == "GET")
        {
            // Si l'id est correctement renseigné dans l'url au affiche les données dans un formulaire 
            if ($data['id'])
            {
                $filtre = array("n.id = $data[id]");
                $dataReponse = $newsletter->findNewsletterBy($field = array(), $filtre);
                // S'il y a un résultat
                if (!empty($dataReponse))
                {
                    $alert = array();
                }
                else
                {
                    $alert = array("danger", "La newsletter que vous voulez modifier n'est pas modifiable!");
                    $this->afficher(array(), $alert);
                    exit();
                }
            }
            else
            {
                // Redirection vers les produits
                $dataReponse = array('salle' => false,);
                $alert = array("danger", "La newsletter que vous voulez modifier n'existe pas!");
                $this->afficher(array(), $alert);
                exit();
            }
        }
        
        // Si le formulaire est soumis
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            $date = new \DateTime("now");
            $date_creation = $date->format("Y-m-d H:i");
            $dataSession = $request->getParameters("SESSION");
            $dataPlus = array("id" => $data['id'],"date_creation" => $date_creation, "date_envoi" => null, "membre_id" => $dataSession["Auth"]->id);
            $data = $request->checkParameters("newsletter", $dataPost, $dataPlus);
            $dataReponse = $request->SpecialHTML($data,true);
            // On vérifie s'il ya des erreurs dans les données envoyées
            if (!in_array(false, $dataReponse, true))
            {
                // On tente de sauvegarde  les données.
                unset($dataPlus['date_envoi']);
                $dataSave = $dataReponse + $dataPlus;
                // On récupère la reponse 
                $reponse = $newsletter->modifierNewsletter($dataSave);
                $alert = ($reponse) ? array("success","La newsletter a été modifée") : array("danger","La newsletter n'a pas été modifiée! Réessayez plus tard!") ;
                $request->redirection("newsletter","afficher",$alert);
                exit();
            }
            else
            {
                $alert = array("danger", "La newsletter n'a pas été modifiée! Vérifiez votre formulaire!");
            }
        }

        // On affiche les resultats
        $this->render("newsletter/modifier.php", array(
            "title" => "Modifier une newsletter",
            "dataReponse" => $dataReponse,
            "alert" => $alert
        ));
    }

    /*
     * Permet supprimer une newsletter.
     * @parameters (array) $arg  avec $arg[0] => id de la newsletter
     *
     */

    public function supprimer($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $newsletter = $this->getRepository('newsletter');
        $data['id'] = (isset($arg[0])) ?  (int)$arg[0] : false;
        $request->valideParameters($data, "newsletter");
        if($data['id'])
        {
            $reponse = $newsletter->supprimerNewsletter($arg[0]);
            $alert = ($reponse) ? array("success", "La newsletter a été supprimée!") : array("danger", "Désolé!, la newsletter n'a pas été supprimée! Réessayez plus tard");
        }
        else
        {
            $alert = array("danger", "La newsletter n'a pas été supprimée! Vérifiez votre formulaire!");
        }
        $request->redirection("newsletter","afficher",$alert);
        exit();
    }
    
    public function envoyer($arg = array())
    {
        
       $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $data['id'] = (isset($arg[0])) ?  (int)$arg[0] : false;
        $request->valideParameters($data, "newsletter");
        $newsletter = $this->getRepository("newsletter");
        $membres = $newsletter->findMembreInscrit();
        $countMembres = count($membres);
        

        if ($request->getMethod() == "GET")
        {
            // Si l'id est correctement renseigné dans l'url au affiche les données dans un formulaire 
            if ($data['id'])
            {
                $filtre = array("n.id = $data[id]");
                $dataReponse = $newsletter->findNewsletterBy($field = array(), $filtre);
                // S'il y a un résultat
                if(!empty($dataReponse))
                {
                    $alert = array();
                }
                else
                {
                    $alert = array("danger", "La newsletter que vous voulez modifier n'est pas modifiable!");
                    $this->afficher(array(), $alert);
                    exit();
                }
            }
            else
            {
                // Redirection vers les produits
                $dataReponse = array('salle' => false,);
                $alert = array("danger", "La newsletter que vous voulez modifier n'existe pas!");
                $this->afficher(array(), $alert);
                exit();
            }
        }
        
        if($request->getMethod() === "POST" && $data['id'])
        {
            if($data['id'])
            {
                    $dataReponse = array();
                    $newsletters = $newsletter->findNewsletterBy(array(), array("n.id = '$data[id]'"));
                    if(count($newsletters)=== 1)
                    {
                        // si on compte membre a été créer on envoie un mail au membre
                        $sujet = $newsletters[0]->titre;
                        $message = $this->cleanAntiSlash($newsletters[0]->contenu);
                        $message = str_replace("&amp;nbsp;", "&nbsp;", $message);
                        $expediteur = array("Lokisalle" => CONTACT_MAIL_LOKISALLE);
                        $destinateur = array(); 
                        foreach($membres as $key => $values)
                        {
                            $destinateur["$values->prenom $values->nom"] = $values->email;
                        }
                        $email = new SendEmail($expediteur, $destinateur, $sujet, $message);
                        if($email->send())
                        {
                            $alert = array("success","Félicitation, la newsletter a été envoyée avec success!");
                            $dataSave = array("id"=>$data['id'], "date_envoi" => date("Y-m-d H:i"));
                            $newsletter->modifierNewsletter($dataSave);
                        }
                        else
                        {
                            $alert = array("danger", "La newsletter n' a pas été envoyée , merci de réessayer plus tard!");
                            
                        }
                        $request->redirection("newsletter","afficher",$alert);
                        exit();
                         
                    }
                    else
                    {
                        $alert = array("danger","La newsletter n'existe pas!");
                    }
            }
            else
            {
                $alert = array("danger", "Impossible d'envoyer la newsletter car elle n'existe pas!");
            }

            
        }
         // On affiche les resultats
         $this->render("newsletter/envoyer.php", array(
             "title" => "Envoyer une newsletter",
             "dataReponse" => $dataReponse,
             "countMembres" => $countMembres,
             "alert" => $alert
         ));
    }
}

?>