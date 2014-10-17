<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;
use SendEmail\SendEmail;

class statiqueController extends Controller
{

    public function mentions()
    {
        $this->render("statique/mentions.php", array(
            "title" => "Mentions légales"
        ));
    }

    public function conditions()
    {
        $this->render("statique/conditions.php", array(
            "title" => "Conditions générales de ventes"
        ));
    }

    public function plan()
    {
        $this->render("statique/plan.php", array(
            "title" => "Plan du site"
        ));
    }
    
    
    public function contact()
    {
        $request = new Request();
        $dataPost = $alert = array();
        if($request->getMethod() === "POST")
        {
            
            $dataPost = $request->getParameters("POST");
            $request->valideParameters($dataPost, "membre");
            if(isset($dataPost['message']))
            {
                $dataPost['message'] = $request->valide($dataPost['message'],"text", $option = array(), $null = false);
            }
            else
            {   
              $dataPost['message'] = false;  
            }
            
            if (!in_array(false, $dataPost, true))
            {
                if($request->isConnected())
                {
                    $dataSession = $request->getParameters("SESSION");
                    $dataPost["nom"] = $dataSession["Auth"]->nom;
                    $dataPost["prenom"] = $dataSession["Auth"]->prenom;
                    $dataPost["email"] = $dataSession["Auth"]->email; 
                    $sujet = "Un membre essaye de rentrer en contact avec vous";
                }
                else
                {
                    $sujet = "Quelqu'un essaye de rentrer en contact avec Lokisalle";
                }
                // Envoyer email
                $message = "Expéditeur: ".ucfirst($dataPost['prenom'])." ".ucfirst($dataPost['nom'])."<br/>";
                $message = "Email de l'expéditeur: $dataPost[email]<br/>";
                $message .= "Message: <br/>$dataPost[message]";
                $expediteur = array("$dataPost[prenom] $dataPost[nom]" => $dataPost["email"]);
                $destinateur = array("Lokisalle" => CONTACT_MAIL_LOKISALLE);
                $email = new SendEmail($expediteur, $destinateur, $sujet, $message);
                
                $alert = ($email->send()) ? array("success","Félicitation, votre message nous a bien été envoyé. Notre équipe vous répondra dans les plus brefs délais.") : array("danger", "Désolé, votre message ne nous est pas parvenu! Merci de réessayer plus tard!");
                $dataPost = array();
                if($alert[0] === "success")
                {
                    $request->redirection("statique","contact", $alert);
                    exit();
                }
                
            }
            else
            {
                $alert = array("danger", "<strong>Des champs sont mals renseignés</strong>, vérifier votre formulaire!");
            }
        }
        if (isset($_SESSION["message"]) && !empty($_SESSION["message"]))
        {
            $request->getParameters("SESSION");
            $mes = $_SESSION["message"];
            unset($_SESSION["message"]);
            $alert = $mes;
        }
        $this->render("statique/contact.php", array(
                    "title" => "Nous contacter",
                    "dataReponse" => $dataPost,
                    "alert" => $alert
            
        ));
    }

}

?>