<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;
use Upload\qqFileUploader;
use SendEmail\SendEmail;

class membreController extends Controller
{

    public function connexion()
    {
        $request = new Request();
        $this->returnProfil($request);
        
        $membre = $this->getRepository('membre');
        if ($request->getMethod() == "POST")
        {
            // On récupère les valeurs des données envoyées par le formulaire
            $dataPost = $request->getParameters("POST");
            if (isset($dataPost['rememberMe']))
            {
                unset($dataPost['rememberMe']);
                $rememberMe = true;
            }
           
            // On prépare le filtre de la requète
            $filtre = array("m.statut_membre = '1'");
            foreach ($dataPost as $key => $values)
            {
                if ($key == "mdp")
                {
                    $request->getParameters("COOKIE");
                    $values = (isset($_COOKIE["USESSIONLOKI"]) && !empty($_COOKIE["USESSIONLOKI"])) ? $values : MD5($values);
                
                }
                array_push($filtre, "m.$key = '$values'");
            }
            $reponse = $membre->findMembreBy($filtre);

            if (count($reponse))
            {
                // On créer le cookie de connexion
                if (isset($rememberMe))
                {
                    if(!isset($_COOKIE["USESSIONLOKI"]) && empty($_COOKIE["USESSIONLOKI"]))
                    {
                        setcookie('USESSIONLOKI', md5($dataPost['mdp']).$reponse[0]->id, time()+ 3600 * 24 *31, '/lokisalle', DOMAINE, false, true);
                    }    
                }
                else
                {
                   setcookie('USESSIONLOKI', null, time()- 3600 * 24 *31, '/lokisalle', DOMAINE,false, true);
                }
                
                $alert = array();
                $_SESSION['Auth'] = $reponse[0];
                $reponse = array("reponse" => "valide");
                if (!$request->isAjax())
                {
                    $this->profil();
                    exit();
                }
            }
            else
            {
                $reponse = array("reponse" => false);
                $alert = array("danger", "Votre pseudo ou mot de passe est incorrect!");
            }
            // On retourne la réponse au format JSON
            if ($request->isAjax())
            {
                echo json_encode(array("reponse" => $reponse['reponse'], "alert" => $alert));
            }
            else
            {
                // On affiche les resultats
                $this->render("membre/connexion.php", array(
                    "title" => "Inscription",
                    "dataReponse" => $reponse,
                    "alert" => $alert
                ));
            }
        }

        if ($request->getMethod() == "GET")
        {
            $request->getParameters("COOKIE");
            if(isset($_COOKIE["USESSIONLOKI"]) && !empty($_COOKIE["USESSIONLOKI"]))
            {    
                $length = strlen($_COOKIE["USESSIONLOKI"])-1;
                $mdp = substr($_COOKIE["USESSIONLOKI"], 0, 32);
                $id = substr($_COOKIE["USESSIONLOKI"], 32, $length);
                $filtre = array("m.mdp = '$mdp'","m.id = '$id'", "m.statut_membre = '1'");
                $reponse = ($membre->findMembreBy($filtre)) ? $membre->findMembreBy($filtre) : array();
                if(count($reponse)) $reponse[0]->mdp = $mdp;

            }
            else
            {
                $reponse = array();
            }
            $alert = array();
            if ($request->isAjax())
            {
                $rep["mdp"] = (isset($reponse[0]->mdp)) ? $reponse[0]->mdp : null;
                $rep["pseudo"] = isset($reponse[0]->pseudo)? $reponse[0]->pseudo : null;
                echo json_encode(array("reponse" => $rep));
            }
            else
            {// On affiche les resultats
                $this->render("membre/connexion.php", array(
                    "title" => "connexion",
                    "dataReponse" => $reponse,
                    "alert" => $alert
                ));
            }
        }
    }

    public function deconnexion()
    {
        if (isset($_SESSION['Auth']) && !empty($_SESSION['Auth']))
        {
            $request = new Request();
            unset($_SESSION['Auth']);
            if (isset($_SESSION['panier']))
                unset($_SESSION['panier']);
            if ($request->isAjax())
            {
                echo json_encode(array("reponse" => "deconnexion"));
            }
            else
            {
                $request->redirection("produit", "accueil");
            }
        }
    }

    private function returnProfil(Request $request)
    {
        if ($request->isConnected())
        {
            $this->profil();
            exit();
        }
    }
    
    public function inscription()
    {
        $request = new Request();
        // if the member is connected , you redirect at his profil.
        $this->returnProfil($request);
        
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            $dataPlus = array("statut_membre" => "0", "role_id" => 1, "token_valid" => null, "token_mdp" => null, "photo" => "gravatar.jpg", "token_secu" => md5(rand(1, 200) * time()));
            $data = array_merge($dataPost,$dataPlus);
            // if the datas are valid then we save them
   
            if ($request->ParametersIsValid('membre',$data))
            {
                // we hydrate member's Objet
                $member= $this->getEntity("membre",$data);
                debug($member);
                //We save the member
                $manager = $this->getRepository('membre');
                
                $reponse = $manager->create($member);
                $alert = $reponse['alert'];
                $reponse = $reponse['reponse'];
                if (empty($reponse['reponse']) && !$request->isAdmin())
                {
                    // After the member has created , we send a email
                    $sujet = 'Activation de votre compte';
                    $message = '<h3>Bonjour ,' . ucfirst($member->getPrenom()) . ' ' . strtoupper($member->getNom()) . '.</h3>' . "\r\t\t" .
                            '<p>Pour activer votre compte, <strong><a href="' . WEBROOT . 'web/index.php?controller=membre&action=activation&email=' . $member->getEmail(). '&token_secu=' . $member->getToken_secu(). '"> cliquez sur ce lien</a></strong>.</p>' . "\r\t\t" .
                            '<p>Cordialement,</p>' . "\r\t\t" .
                            '<strong><p>L\'équipe LOKISALLE.</p></strong>';
                    $expediteur = array("Lokisalle" => CONTACT_MAIL_LOKISALLE);
                    $destinateur = array($member->getPrenom()." ".$member->getNom() => $member->getEmail());
                    $email = new SendEmail($expediteur, $destinateur, $sujet, $message);
                    $email->send();
                    $alert = array("success","Vous venez de recevoir un mail pour activer votre compte! ");
                }

                
            }
            else
            {
                $reponse = $dataPost;
                $alert = array();
            }

            if ($request->isAjax())
            {
                echo json_encode(array("reponse" => $reponse, "alert" => $alert));
            }
            else
            {
                // On affiche les resultats
                $this->render("membre/inscription.php", array(
                    "title" => "Inscription",
                    "dataReponse" => $reponse,
                    "alert" => $alert
                ));
            }
        }
        if ($request->getMethod() == "GET")
        {
            $reponse = $alert = array();
            // On affiche les resultats
            $this->render("membre/inscription.php", array(
                "title" => "Inscription",
                "dataReponse" => $reponse,
                "alert" => $alert
            ));
        }
    }

    public function activation($arg)
    {
        $request = new Request();
        $this->returnProfil($request);
        $membre = $this->getRepository('membre');
        $filtre = array("m.statut_membre = '0'");

        if (count($arg) >= 2)
        {
            array_push($filtre, "m.email = '$arg[0]'");
            array_push($filtre, "m.token_secu = '$arg[1]'");
            $result = $membre->findMembreBy($filtre);
            if (count($result == 1))
            {
                $result[0]->statut_membre = "1";
                $result[0]->token_secu = null;
                $_SESSION['Auth'] = $result[0];
                unset($result[0]->nom_reel);
                if ($membre->modifierMembre((array) $result[0]))
                {
                    $this->profil();
                    exit();
                }
            }
        }
    }

    public function passOublie()
    {
        $request = new Request();
        $this->returnProfil($request);
        $membre = $this->getRepository('membre');
        if ($request->isAjax())
        {
            if ($request->getMethod() == "POST")
            {
                // On récupère les valeurs des données envoyées par le formulaire
                $dataPost = $request->getParameters("POST");
                // On prépare le filtre de la requète
                $filtre = array("m.statut_membre = '1'");
                if (isset($dataPost['email']) && !empty($dataPost['email']))
                {
                    array_push($filtre, "m.email = '$dataPost[email]'");
                    $reponse = $membre->findMembreBy($filtre);
                    // On vérifie que le membre existe
                    if (count($reponse))
                    {
                        $token_valid = time() + 86400;
                        $token_mdp = md5(rand(1, 100) * time());
                        $dataSave = array("email" => $dataPost['email'], "token_valid" => $token_valid, "token_mdp" => $token_mdp, "id" => $reponse[0]->id);
                        $reponseUpdate = $membre->save($dataSave);
                        if ($reponseUpdate)
                        {
                            $alert = array("success", "Vous venez de recevoir un email pour changer votre mot de passe!");
                            // On envoi le mail
                            $sujet = 'Mot de passe oublié';
                            $message = '<h3>Bonjour ,' . ucfirst($reponse[0]->prenom) . ' ' . strtoupper($reponse[0]->nom) . '.</h3>' . "\r\t\t" .
                                    '<p>Pour changer votre mot de passe, <strong><a href="' . WEBROOT . 'web/index.php?controller=membre&action=initPass&email=' . $dataSave['email'] . '&token_mdp=' . $token_mdp . '"> cliquez sur ce lien</a></strong>.</p>' . "\r\t\t" .
                                    '<p>Cordialement,</p>' . "\r\t\t" .
                                    '<strong><p>L\'équipe LOKISALLE.</p></strong>';
                            $expediteur = array("Lokisalle" => CONTACT_MAIL_LOKISALLE);
                            $destinateur = array(ucfirst($reponse[0]->prenom) . " " . $reponse[0]->nom => $dataSave['email']);
                            $email = new SendEmail($expediteur, $destinateur, $sujet, $message);
                            $email->send();
                            $data = array();
                        }
                        else
                        {
                            $alert = array("warning", "Ereur SQL!");
                        }
                    }
                    else
                    {
                        $alert = array("danger", "L'utilisateur n'existe pas!");
                    }
                }
                else
                {
                    $alert = array("danger", "Il faut renseigner votre email!");
                }
                // On retourne la réponse au format JSON
                echo json_encode(array("reponse" => $alert));
            }
        }
    }

    public function initPass($arg = array())
    {
        $request = new Request();
        $this->returnProfil($request);
        $dataGet = array();
        $membre = $this->getRepository('membre');
        // le formulaire a été soumis
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            if (isset($dataPost['email']) && isset($dataPost['token_mdp']) && isset($dataPost['mdp']) && isset($dataPost['mdp2']))
            {
                $data = array("mdp" => $dataPost['mdp'], "email" => $dataPost['email'], "token_mdp" => $dataPost['token_mdp']);
                if ($dataPost['mdp2'] === $dataPost['mdp'])
                {
                    $request->valideParameters($data, "membre");
                    if (!in_array(false, $data, true))
                    {
                        //On verifie que le membre existe
                        $filtre = array("m.email = '$data[email]'", "m.token_mdp = '$data[token_mdp]'", "m.token_valid >" . time());
                        $reponse = $membre->findMembreBy($filtre);
                        if (count($reponse) === 1)
                        {
                            //Effectuer le sauvegarde de nouveau mot de passe
                            $data['token_mdp'] = $data['token_valid'] = null;
                            $data['mdp'] = md5($data['mdp']);
                            $data['id'] = $reponse[0]->id;
                            $saveMembre = $membre->modifierMembre($data, false);
                            ($saveMembre) ? $alert = array("success", "<strong>Félicitation!</strong> Votre mot de passe a été modifié avec success") : array("danger", "<strong>Désolé!</strong> Votre mot de passe n'a pas été modifié!");
                        }
                        else
                        {
                            $alert = array("danger", "<strong>Désolé!</strong> Votre mot de passe n'a pas été modifié car la date de validité pour le changer a expiré! Merci de refaire une demande pour réinialiser votre mot de passe!");
                        }
                        $display = false;
                    }
                    else
                    {
                        $dataGet = array("email" => $dataPost['email'], "token_mdp" => $dataPost['token_mdp']);
                        $display = true;
                        $alert = array("danger", "<strong>Votre mot de passe doit comporter entre 3 et 14 caractères!</strong> Vérifiez votre formulaire.");
                    }
                }
                else
                {
                    $dataGet = array("email" => $dataPost['email'], "token_mdp" => $dataPost['token_mdp']);
                    $display = true;
                    $alert = array("danger", "<strong>Les deux mots de passe ne sont pas identiques!</strong> Vérifiez votre formulaire.");
                }
            }
            else
            {
                $display = true;
                $alert = array("danger", "<strong>Erreur d'enregistrement!</strong> Vérifiez votre formulaire.");
                $data = array(false);
            }
        }
        if ($request->getMethod() == "GET")
        {
            if (empty($arg) || (!isset($arg[0]) && empty($arg[0])) || (!isset($arg[1]) && empty($arg[1])))
            {
                $alert = array("danger", "<strong>Les informations dans l'url ne permettent pas d'identifier un compte!</strong> Merci de la verifier.");
                $display = false;
            }
            else
            {
                // on enléve les balise html
                $request->clean($arg);
                $membre = $this->getRepository("membre");
                $filtre = array("m.email = '$arg[0]'", "m.token_mdp = '$arg[1]'");
                $membres = $membre->findMembreBy($filtre);
                if (count($membres) === 1)
                {
                    $alert = array("info", "<strong>Pour changer le mot de passe de votre compte</strong>, veuillez remplir le formulaire ci-dessous.");
                    $dataGet = array("email" => "$arg[0]", "token_mdp" => "$arg[1]");
                    $display = true;
                }
                else
                {
                    $alert = array("danger", "Pour changer de mot de passe, vous devez faire une demande via le formulaire de connexion!");
                    $display = false;
                }
            }
        }
        // tu vas afficher formulaire pour changer de mot de passe
        // On affiche les resultats
        $this->render("membre/oublieMdp.php", array(
            "title" => "Changer votre mot de passe",
            "display" => $display,
            "dataGet" => $dataGet,
            "alert" => $alert
        ));
    }

    public function newsletter($arg = array())
    {
        $request = new Request();
        $connected = ($request->isConnected()) ? true : false;
        if ($connected)
        {
            $dataSession = $request->getParameters("SESSION");
            if ($request->getMethod() === "GET" && empty($arg))
            {
                $alert = array("info", "<strong>Si vous souhaitez vous abonner à la newsletter et recevoir les actualités de lokisalle</strong>, <a href=\"index.php?controller=membre&action=newsletter&accord=true\">cliquer ici</a>.");
            }
            else
            {
                $membre = $this->getRepository("membre");
                $membres = $membre->modifierMembre(array("id" => $dataSession['Auth']->id, "statut_newsletter" => "1"), $check = false);
                $alert = ($membres) ? array("success", "<strong>Félicitation!</strong> Vous êtes inscrit à la newsletter lokisalle.") : array("danger", "<strong>Désolé!</strong> Vous êtes déjà inscrit à la newsletter lokisalle");
            }
        }
        else
        {

            $alert = array("danger", "<strong>Désolé!</strong> Vous devez être connecté pour vous inscrire à la newsletter. <a class=\"connectLink\" style=\"color:#B80000\" href=\"index.php?controller=membre&action=connexion\" data-target=\"#myModalConnexion\" data-toggle=\"modal\" onclick=\"return false;\"><span class=\"glyphicon glyphicon-chevron-right\"></span>&nbsp;Vous connectez</a>");
        }
        $this->render("membre/newsletter.php", array(
            "title" => "Inscription newsletter",
            "alert" => $alert
        ));
    }

    public function profil($arg = array(), $message = array())
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : true;
        $id = $request->getParameters("SESSION");
        $id = $id['Auth']->id;
        $membre = $this->getRepository('membre');
        $filtre = array("m.id = $id", "m.statut_membre = '1'");
        $dataReponse = $membre->findMembreBy($filtre);
        // S'il y a un résultat
        if (!empty($dataReponse))
        {
            $alert = array();
        }
        else
        {
            $alert = array("danger", "Le compte n'existe pas !");
        }
        $arg[0] = (isset($arg[0])) ? $arg[0] : 0;
        $page = $request->valide($arg[0], "int", array("min" => 0, "max" => 9999999), $null = false);
        $this->start = ($page != false) ? $page : 0;
        $this->end = 3;
        $tri = array("LIMIT" => $this->start . "," . $this->end);
        $commande = $membre->getCommandeByMembre($id, $tri);
        $countCommande = count($membre->getCommandeByMembre($id, array()));
        if ($countCommande > 0)
        {
            $result = "";
            foreach ($commande as $k => $values)
            {
                $bg = ($k % 2 === 0) ? "#f8f8f8" : "#fff";
                $result .= '<tr style="font-size:12px; background:' . $bg . '">';
                foreach ($values as $key => $val)
                {
                    if ($key != "membre_id" && $key != "montant")
                    {
                        $result .= "<td>" . $this->cleanAntiSlash($val) . "</td>";
                    }
                    if ($key == "montant")
                    {
                        $result .= "<td>$val €</td>";
                    }
                }
                $result .= "</tr>";
            }
        }
        else
        {
            $result = "";
        }
        $url = "index.php?controller=membre&action=profil";
        $pagination = $this->pagination($countCommande, $url);

        if (!empty($message))
            $alert = $message;
        // On affiche les resultats
        $this->render("membre/profil.php", array(
            "title" => "Votre profil",
            "dataReponse" => $dataReponse,
            "alert" => $alert,
            "pagination" => $pagination,
            "commande" => $result
        ));
    }

    public function uploadImg()
    {
        $request = new Request();
        $dataFiles = $request->getParameters("FILES");
        $uploader = new qqFileUploader();
        $uploadDir = INDEXROOT . "/lokisalle/images/uploads/";
        $saveImg = $uploader->handleUpload($uploadDir);
        // Si le Upload c'est bien passé alors on redimensionne les images
        if (isset($saveImg['success']) && $saveImg['success'] === true)
        {
            $_POST['photo'] = substr($saveImg['img'], 0, -4);
            $uploader->rezise(500, 250, 250, $uploadDir, INDEXROOT . "/lokisalle/images/uploads/resize/");
            $imgResult = $uploader->crop(INDEXROOT . "/lokisalle/images/uploads/resize/", INDEXROOT . "/lokisalle/images/membres/", 250, 250);
            $imgResult['img'] = utf8_encode($imgResult['img']);
            $imgResult['alt'] = utf8_encode($imgResult['alt']);
            if ($request->isUploadFileAjax())
            {
                echo json_encode($imgResult);
            }
        }
        else
        {
            if ($request->isUploadFileAjax())
            {
                echo json_encode($saveImg);
            }
        }
    }

    public function modifier()
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : $SESSION = $request->getParameters("SESSION");
        $membre = $this->getRepository('membre');
        if ($request->getMethod() == "POST")
        {
            if ($request->isUploadFile())
            {
                $this->uploadImg();
            }
            $dataPost = $request->getParameters("POST");
            // $dataplus=> Correspond au données rajoutées juste avant l'enregistrement dans BDD(Ils n'apparaissent pas dans le formulaire)
            $dataPlus = array("statut_membre" => "1", "role_id" => $SESSION['Auth']->role_id, "token_valid" => null, "token_mdp" => null, "mdp" => "admin", "token_secu" => null, "id" => $SESSION['Auth']->id);
            // On vérifie les données récupérées avec les valeurs attendues

            $dataReponse = $request->checkParameters("membre", $dataPost, $dataPlus);
            // On vérifie s'il ya des erreurs dans les données envoyées

            if (!in_array(false, $dataReponse, true))
            {
                // On tente de sauvegarde  les données.
                unset($dataPlus["mdp"]);
                $dataSave = $dataReponse + $dataPlus;
                // On récupère la reponse 
                $reponse = $membre->modifierMembre($dataSave);
                $alert = $reponse['alert'];
                $reponse = $reponse['reponse'];
                if (count($reponse) === 0 && $alert[0] === "success")
                {
                    $this->profil($alert);
                    exit();
                }
            }
            else
            {
                $reponse = $dataReponse;
                $alert = array("danger", "Le compte n'a pas été modofié! Vérifiez votre formulaire!");
                ;
            }
        }
        if ($request->getMethod() == "GET")
        {
            $membre = $this->getRepository('membre');
            $filtre = array("m.id=" . $SESSION['Auth']->id);
            $dataReponse = $membre->findMembreBy($filtre);
            // S'il y a un résultat
            if (!empty($dataReponse))
            {
                $alert = array();
            }
            else
            {
                $alert = array("danger", "Le compte n'existe pas!");
            }
        }
        // On affiche les resultats
        $this->render("membre/modifier.php", array(
            "title" => "Modifier votre profil",
            "dataReponse" => $dataReponse,
            "alert" => $alert,
        ));
    }

    public function afficher($arg = array(), $alert = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "pseudo" => "asc", "prenom" => "asc", "nom" => "asc", "email" => "asc", "adresse" => "asc", "cp" => "asc", "nom_reel" => "asc", "statut_membre" => "asc", "role_id" => "asc");
        $membre = $this->getRepository('membre');
        $filtre = array();
        $tri = $this->triAffichage($arg, $triAffichage);
        //On récupère les données
        $membres = $membre->findMembreBy($filtre, $tri);

        $result = "";
        foreach ($membres as $k => $value)
        {
            $bg = ($k % 2 === 0) ?  "#f8f8f8" : "#fff";
            $result .= '<tr style="background:' . $bg . '">';
            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    $result .= "<td>" . $this->cleanAntiSlash($val) . "</td>";
                }
            }
            $result .= '<td><a class="delete" data="de supprimer un membre" href="index.php?controller=membre&action=supprimer&id=' . $value->id . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $result .= "</tr>";
        }

        $nbreMembre = count($membre->findMembreBy($filtre));
        $url = "index.php?controller=membre&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbreMembre,$url);
        if ($request->isAjax())
        {
            echo json_encode(array("reponse" => "success", "result" => $result, "pagination" => $pagination));
        }
        else
        {
            // On affiche les resultats
            $this->render("membre/afficher.php", array(
                "title" => "Tous les membres",
                "membres" => $result,
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
        $membre = $this->getRepository('membre');
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            // $dataplus=> Correspond au données rajoutées juste avant l'enregistrement dans BDD(Ils n'apparaissent pas dans le formulaire)
            $dataPlus = array("statut_membre" => "1", "role_id" => 0, "statut_newsletter" => 1, "token_valid" => null, "token_mdp" => null, "photo" => "gravatar.jpg", "token_secu" => md5(rand(1, 200) * time()));
            // On vérifie les données récupérées avec les valeurs attendues
            $dataReponse = $request->checkParameters("membre", $dataPost, $dataPlus);
            // On vérifie s'il ya des erreurs dans les données envoyées
            if (!in_array(false, $dataReponse, true))
            {
                // On tente de sauvegarde  les données.
                $dataSave = $dataReponse + $dataPlus;
                $dataSave['mdp'] = md5($dataSave['mdp']);
                // On récupère la reponse 
                $reponse = $membre->create($dataSave, true);
                $alert = $reponse['alert'];
                $reponse = $reponse['reponse'];
            }
            else
            {
                $reponse = $dataReponse;
                $alert = array("danger", "Le compte administrateur n'a pas été créer! Vérifiez votre formulaire!");
            }
        }
        else
        {
            $reponse = array();
            $alert = array();
        }
        // On affiche les resultats
        $this->render("membre/ajouter.php", array(
            "title" => "Ajouter un administrateur",
            "dataReponse" => $reponse,
            "alert" => $alert,
        ));
    }

    public function supprimer($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $membre = $this->getRepository('membre');
        $id = (!empty($arg)) ? $arg[0] : null;
        if ($id !== null && $id !== 0)
        {
            $alert = $membre->supprimerMembre($arg[0]);
        }
        else
        {
            $alert = array("danger", "Le compte n'a pas été supprimé car il n'existe pas! \n");
        }
        $this->afficher(array(), $alert);
        exit();
    }

}
