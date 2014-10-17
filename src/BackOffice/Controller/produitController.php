<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;
use SendEmail\SendEmail;

class produitController extends Controller
{
    /*
     * @variable (integer)
     */

    private $nbProduit = 0;
    private $sousTotal = 0;
    private $SearchCritrer = array();

    /* Affichage de la page d'accueil */

    public function accueil()
    {
        $request = new Request();
        $connected = ($request->isConnected()) ? true : false;
        // On va chercher les données correspondantes aux trois dernières offres
        $produit = $this->getRepository('produit');
        $filtre = array("p.etat = '0'", "p.date_arrivee >= NOW()");
        $tri = array("ORDER BY" => "RAND()", "LIMIT" => "0,3");
        //On récupère les données
        $produits = $produit->findProduitBy($filtre, $tri);
        $produit->filtreText($produits, "titre", 17);
        $produit->specialMasjuscule($produits, "titre");
        // On va chercher les données correspondantes aux autres suggestions
        if($produits)
        {
            $id = "";
            foreach ($produits as $valeurs)
            {
                // On recupère les 3 ids des produits correspondants au trois dernières offres pour éviter les doublons dans l'affichage.
                $id .= "'$valeurs->id', ";
            }
            $id = substr($id, 0, -2);
            $filtre = array("p.id NOT IN($id)", "p.etat = '0'", "p.date_arrivee >= NOW()");
            $tri = array("ORDER BY" => "RAND()", "LIMIT" => "0,4");
            //On récupère les données
            $Suggestionproduits = $produit->findProduitBy($filtre, $tri);
            $produit->filtreText($Suggestionproduits, "description", 320);
            $produit->filtreText($Suggestionproduits, "titre", 17);
            $produit->specialMasjuscule($Suggestionproduits, "titre");  
        }
        else
        {
            $Suggestionproduits = array();
        }
        
        
        //On affiche les resultats
        $this->render("produit/accueil.php", array(
            'title' => 'Accueil',
            'produits' => $produits,
            'dataReponse' => array(),
            'connected' => $connected,
            'Suggestionproduits' => $Suggestionproduits
        ));
    }

    /*
     * Permet d'afficher les détails d'un produit suivant son id.
     * @parameters (array) $arg  ou $arg[0] est la valeur de l'id passé dans l'url
     *
     */

    public function detailsProduit($arg = array(), $message = array())
    {
        $request = new Request();
        $connected = ($request->isConnected()) ? true : false;
        $idProduct = (isset($arg[0])) ? $arg[0] : false;
        $id = $request->valide($idProduct, "int", array("min"=>1,"max"=>9999999), $null = false);
        $page = (isset($arg[1])) ? $arg[1] : false;
        $triResult = $request->valide($page, "int", array("min"=>0,"max"=>9999999), $null = false);
        $tri = ($triResult != false) ? $triResult : 0; 
        $this->start = $tri;
        $this->end = 4;
        if ($id)
        {
            // On va chercher les données correspondantes au l'id produit demandé ($id) ainsi que ces commentaires
            $produit = $this->getRepository('produit');
            $filtre = array("p.etat = '0'");
            $produits = $produit->findDetailsProduit($id, $tri);
            // On vérifie que le membre n'a pas déjà passé un commentaire
            if (!empty($produits['avis']) && $request->isConnected())
            {
                foreach ($produits['avis'] as $key => $value)
                {
                    $dataSession = $request->getParameters("SESSION");
                    if ($value->membre_id === $dataSession["Auth"]->id)
                    {
                        $dejaCommente = true;
                        break;
                    }
                    else
                    {
                        $dejaCommente = false;
                    }
                }
            }
            else
            {
                $dejaCommente = false;
            }
            
            // On récupère les données des produits similaires à l'id produit demandé (similitude => même ville)
            if ($produits['produit'] !== false)
            {
                $produit->specialMasjuscule($produits['produit'], "titre");
                $ville = $produits['produit'][0]->nom_reel;
                $filtre = array("p.id NOT IN($id)", "v.nom_reel = '$ville'", "p.etat = '0'", "p.date_arrivee >= NOW()");
                $tri = array("ORDER BY" => "RAND()", "LIMIT" => "0,4");
                $Suggestionproduits = $produit->findProduitBy($filtre, $tri);
                $produit->filtreText($Suggestionproduits, "titre", 17);
                $produit->specialMasjuscule($Suggestionproduits, "titre");
            }
            else
            {
                $Suggestionproduits = null;
            }
        }
        else
        {
            $this->accueil();
            exit();
        }
        $url = "index.php?controller=produit&action=detailsProduit&id=$id";
        $pagination = $this->pagination($produits['totalAvis'],$url);
        $alert = (!empty($message)) ? $message : array();
        // On affiche les resultats
        $this->render("produit/detailsProduit.php", array(
            'title' => 'Détails du produit',
            'produits' => $produits,
            'connected' => $connected,
            'alert' => $alert,
            "pagination" => $pagination,
            'dejaCommente' => $dejaCommente,
            'Suggestionproduits' => $Suggestionproduits
        ));
    }

    public function avis($arg = array())
    {
        $request = new Request();
        $connected = ($request->isConnected()) ? true : false;
        $data["id"] = (isset($arg[0])) ? $arg[0]  : false;
        $request->valideParameters($data, "avis");
        if($data["id"])
        {
            if ($request->getMethod() === "POST")
            {
                $dataPost = $request->getParameters("POST");
                $dataPlus = array("date" => date("Y-m-d H:i:s"), "membre_id" => $_SESSION['Auth']->id);
                $dataReponse = $request->checkParameters("avis", $dataPost, $dataPlus);
                // On vérifie s'il ya des erreurs dans les données envoyées
                if (!in_array(false, $dataReponse, true))
                {
                    // On tente de sauvegarde  les données.
                    $dataSave = $dataReponse + $dataPlus;
                    $produit = $this->getRepository("produit");
                    if ($produit->saveAvis($dataSave))
                    {
                        $alert = array("success", "<strong>Félicitation!</strong> Votre avis a été enregistré!");
                        $dataReponse = array();
                    }
                    else
                    {
                        $alert = array("danger", "Votre avis n'a pas été enregistré! Réessayer plus tard!");
                    }

                }
                else
                {
                    $alert = array("danger", "Votre avis n'a pas été enregistré! Vérifiez votre formulaire!");
                }
            }
            else
            {
                //Formulaire non soumis
                $dataReponse = array();
                $alert = array();
            }
            $request->redirectionHeader("index.php?controller=produit&action=detailsProduit&id=$arg[0]",$alert);
            exit();    
        }
        else
        {
            $this->accueil();
            exit();
        }
    }

    
    
    public function recherche()
    {
        $request = new Request();
        $connected = $request->isConnected();
        $nbreProduit = array();
        if($request->getMethod() === "POST")
        {
            $dataPost = $request->getParameters("POST");
            $request->clean($dataPost);
            $produit = $this->getRepository("produit");
            // on va vérifier qu'il y a un champ déjà rempli
            $filtre = array();
            if(!empty($dataPost))
            {
                $request->valideParameters($dataPost, "produit");
                $dataPost["capacite"] = $request->valide($dataPost["capacite"], "int", array("min"=>1,"max"=>99999999), false);
                // Si les valeurs attendues ne sont pas bonnes, on les enléve de la recherche
                foreach($dataPost as $key => $value)
                {
                     if($value === false) $dataPost[$key] = "";
                }
                foreach($dataPost as $key => $value)
                {
                    switch ($key)
                    {
                        case "categorie":
                            if(!empty ($value)) array_push($filtre, "categorie LIKE '%$value%'");
                            break;
                        case "date_arrivee":
                            if(!empty ($value))
                            {
                                $date = new \DateTime($value);
                                $dataPost["date_arrivee"] = $date->format('d/m/Y H:i:s');
                                $date = $date->format('Y-m-d H:i:s');
                                array_push($filtre, "date_arrivee >= '$date'");
                            }    
                            break;
                        case "capacite":
                            if(!empty ($value)) array_push($filtre, "capacite  >= '$value'");
                            break;
                        case "ville_france_id":
                            if(!empty ($value)) array_push($filtre, "ville_france_id LIKE '%$value%'");
                            break;
                        case "date_depart":
                            if(!empty ($value))
                            {
                                $date = new \DateTime($value);
                                $dataPost["date_depart"] = $date->format('d/m/Y H:i:s');
                                $date = $date->format('Y-m-d H:i:s');
                                array_push($filtre, "date_depart <= '$date'");
                            }
                            break;
                        default:
                            break;
                    }  
                }
            }
            if(!empty($filtre))
            {
                 array_push($filtre, "date_arrivee > NOW()");
                 array_push($filtre, "etat = '0'");
                 $tri = array("ORDER BY p.date_arrivee" => "ASC");
                 $produits = $produit->findProduitBy($filtre,$tri);
                 $nbreProduit = count($produits);
                 $produit->filtreText($produits, "titre", 17);
                 $produit->specialMasjuscule($produits, "titre");
                 
                 $alert = array();
            }
            else
            {
                $alert = array("danger","Il faut au moins un champ correctement renseigné pour effectuer une recherche");
                $produits =array();
            }   
        }
        else
        {
            $dataPost =  $alert =array();
            $produits = false;
        }
        
        // On affiche les resultats
            $this->render("produit/recherche.php", array(
                "title" => "Rechercher un produit",
                "dataReponse" => $dataPost,
                "produits" => $produits,
                "connected" => $connected,
                "nbreProduit" => $nbreProduit,
                "alert" => $alert
            ));
    }
    
    
    
    public function reservation($arg = array())
    {
        $request = new Request();
        $connected = $request->isConnected();
        if($request->getMethod() === "GET")
        {
            $dataGet = $request->getParameters("GET");
            //$dataGet = $request->valideParameters($dataGet,"produit");
            $produit = $this->getRepository("produit");
            // on va vérifier qu'il y a un champ déjà rempli
            $filtre = array("etat = '0'","date_arrivee > NOW()");
            $this->setTri(array(null, null, 0, 12));
            $arg[0] = (isset($arg[0])) ? $arg[0] : 0;
            $id = $request->valide($arg[0], "int", array("min"=>0,"max"=>9999999), $null = false);
            $this->start = ($id != false) ? $id : 0;
            $tri = array("LIMIT" => $this->start . "," . $this->end);
            $produits = $produit->findProduitBy($filtre, $tri);
            $produit->filtreText($produits, "description", 320);
            $produit->filtreText($produits, "titre", 17);
            $produit->specialMasjuscule($produits, "titre");
            $nbreProduit = count($produit->findProduitBy($filtre));
            $url = "index.php?controller=produit&action=reservation";
            $pagination = $this->pagination($nbreProduit,$url);
        }
        
        
        // On affiche les resultats
            $this->render("produit/reservation.php", array(
                "title" => "Réserver un produit",
                "produits" => $produits,
                "pagination" => $pagination,
                "connected" => $connected
            ));
    }
    
    
    /*
     * Permet d'ajouter un produit.
     * @parameters (array) $arg  ou $arg[0] est l'id de salle (salle_id) lié au produit
     *
     */

    public function ajouter($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $produit = $this->getRepository('produit');
        if (!$request->isAjax())
        {
            if ($request->getMethod() == "POST")
            {
                // Formulaire soumis
                $dataPost = $request->getParameters("POST");
                $dataPlus = array("etat" => "0", "membre_id" => $_SESSION['Auth']->id);
                $dataReponse = $request->checkParameters("produit", $dataPost, $dataPlus);
                if (isset($dataReponse['date_arrivee']) && isset($dataReponse['date_depart']))
                {
                    $dataReponse['date_arrivee'] = $request->supDateNow($dataReponse['date_arrivee']);
                    $dataReponse['date_depart'] = $request->supDateNow($dataReponse['date_depart']);
                }
                // On vérifie s'il ya des erreurs dans les données envoyées
                if (!in_array(false, $dataReponse, true))
                {
                    // On vérifie la date depart > date arrivée
                    $dateArrivee = new \DateTime($dataReponse["date_arrivee"]);
                    $dateDepart = new \DateTime($dataReponse["date_depart"]);
                    if ($dateDepart > $dateArrivee)
                    {
                        // On tente de sauvegarde  les données.
                        $dataSave = $dataReponse + $dataPlus;
                        // On récupère la reponse 
                        $reponse = $produit->saveProduit($dataSave);
                        $alert = $reponse['alert'];
                        $dataReponse = $reponse['reponse'];
                        $request->redirection("produit","afficher",$alert);
                        exit();
                    }
                    else
                    {
                        $dataReponse["date_depart"] = false;
                        $alert = array("danger", "La date de départ doit être supérieure à la date d'arrivée !");
                    }
                }
                else
                {
                    $alert = array("danger", "Le produit n'a pas été enregistré! Vérifiez votre formulaire!");
                }
            }
            else
            {
                //Formulaire non soumis
                $dataReponse = array();
                $alert = array();
            }
            $promotion = $produit->getPromotion();
            $salle = $produit->getSalle();
            // On affiche les resultats
            $this->render("produit/ajouter.php", array(
                "title" => "Ajouter un produit",
                "dataReponse" => $dataReponse,
                "promotion" => $promotion,
                "salle" => $salle,
                "alert" => $alert
            ));
        }
        else
        {
            echo json_encode($produit->searchSalle($arg[0]));
        }
    }

    /*
     * Permet d'afficher la liste de tous les produits.
     * @parameters (array) $arg  && (array) $alert ou la variable permet de personnaliser le message de notification
     *
     */

    public function afficher($arg = array(), $message = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "titre" => "asc", "date_arrivee" => "asc", "date_depart" => "asc", "prix" => "asc", "capacite" => "asc", "categorie" => "asc", "cp" => "asc", "nom_reel" => "asc");
        $produit = $this->getRepository('produit');
        $tri = $this->triAffichage($arg, $triAffichage);
        //On récupère les données
        $produits = $produit->findProduitBy(array("p.date_arrivee >= NOW()"), $tri);

        $result = "";
        foreach ($produits as $k => $value)
        {
            $bg = ($k % 2 === 0) ?  "#f8f8f8" : "#fff";
            $result .= '<tr style="background:' . $bg . '">';
            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    $result .= "<td>".$this->cleanAntiSlash($val)."</td>";
                }
            }
            $result .= '<td><a href="index.php?controller=produit&action=modifier&id=' . $value->id . '" style="color:orange"><span class="glyphicon glyphicon-remove"></span></a></td>';
            $result .= '<td><a class="delete" data="de supprimer un produit" href="index.php?controller=produit&action=supprimer&id=' . $value->id . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $result .= "</tr>";
        }

        $nbreProduit = count($produit->findProduitBy(array("p.date_arrivee >= NOW()")));
        $url = "index.php?controller=produit&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbreProduit,$url);
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
            $this->render("produit/afficher.php", array(
                "title" => "Tous nos produits",
                "produits" => $result,
                "alert" => $alert,
                "triAffichage" => $triAffichage,
                "pagination" => $pagination
            ));
        }
    }

    /*
     * Permet de modifier un produit en fonction de son id.
     * @parameters (array) $arg  ou $arg[0] est l'id du produit
     *
     */

    public function modifier($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $produit = $this->getRepository('produit');
        (!empty($arg)) ? $id = $arg[0] : $id = false;
        if ($request->getMethod() == "GET")
        {
            settype($id, "integer");
            // Si l'id est correctement renseigné dans l'url au affiche les données dans un formulaire 
            if ($id)
            {
                $filtre = array("p.id = $id", "p.etat = '0'", "p.date_arrivee >= NOW()");
                $dataReponse = $produit->findProduitBy($filtre);
                // S'il y a un résultat
                if (!empty($dataReponse))
                {
                    $alert = array();
                }
                else
                {
                    $alert = array("danger", "Le produit n'est pas modifiable car il est actuellement réservé par un client!");
                    $this->afficher(array(), $alert);
                    exit();
                }
            }
            else
            {
                // Redirection vers les produits
                $dataReponse = array('produit' => false,);
                $alert = array("danger", "Le produit que vous voulez modifier n'existe pas!");
                $this->afficher(array(), $alert);
                exit();
            }
        }
        // Si le formulaire est soumis
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            $dataPlus = array("membre_id" => $_SESSION['Auth']->id, "id" => $id);
            $dataReponse = $request->checkParameters("produit", $dataPost, $dataPlus);
            if (isset($dataReponse['date_arrivee']) && isset($dataReponse['date_depart']))
            {
                $dataReponse['date_arrivee'] = $request->supDateNow($dataReponse['date_arrivee']);
                $dataReponse['date_depart'] = $request->supDateNow($dataReponse['date_depart']);
            }
            // On vérifie s'il ya des erreurs dans les données envoyées
            if (!in_array(false, $dataReponse, true))
            {
                // On vérifie la date depart > date arrivée
                $dateArrivee = new \DateTime($dataReponse["date_arrivee"]);
                $dateDepart = new \DateTime($dataReponse["date_depart"]);
                if ($dateDepart > $dateArrivee)
                {
                    // On tente de sauvegarde  les données.
                    $dataSave = $dataReponse + $dataPlus;
                    // On récupère la reponse 
                    $reponse = $produit->saveProduit($dataSave);
                    $alert = $reponse['alert'];
                    $dataReponse = $reponse['reponse'];
                    $request->redirection("produit","afficher",$alert);
                    exit();
                }
                else
                {
                    $dataReponse["date_depart"] = false;
                    $alert = array("danger", "La date de départ doit être supérieure à la date d'arrivée !");
                }
            }
            else
            {
                $alert = array("danger", "Le produit n'a pas été modifié! \n Vérifiez votre formulaire!");
            }
        }
        $promotion = $produit->getPromotion();
        $salle = $produit->getSalle();
        // On affiche les resultats
        $this->render("produit/modifier.php", array(
            "title" => "Modifier un produit",
            "dataReponse" => $dataReponse,
            "promotion" => $promotion,
            "salle" => $salle,
            "alert" => $alert
        ));
    }

    /*
     * Permet de supprimer un produit.
     * @parameters (array) $arg  ou $arg[0] est l'id du produit
     *
     */

    public function supprimer($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $produit = $this->getRepository('produit');
        (!empty($arg)) ? $id = $arg[0] : $id = null;
        if ($id !== null && $id !== 0)
        {
            $alert = $produit->supprimerProduit($arg[0]);
            $request->redirection("produit","afficher",$alert);
            exit();        
        }
        else
        {
            $alert = array("danger", "Le produit n'a pas été supprimé car il n'existe pas! \n");
        }
        $this->afficher(array(), $alert);
    }

    /*
     * Permet d'afficher le panier.
     * @parameters (array) $message  ou $message permet de personnaliser le message de notification.
     *
     */

    public function panier($message = array())
    {
        $request = new Request();
        $connected = ($request->isConnected()) ? true : false;
        $alert = ($connected) ? array() : array("danger", "Vous devez être connecté pour accéder à votre panier!");
        if ($connected)
        {
            if (!$this->basketIsNotNull())
            {
                $alert = array("info", "Votre panier est vide!");
                $connected = false; // Pour filtrer l'affichage du panier
                $result = array();
            }
            else
            {
                // on recupère les données du panier
                $dataPanier = $_SESSION['panier']['article'];
                $ordreAffichage = array("id" => 0, "titre" => 1, "photo" => 2, "date_arrivee" => 3, "date_depart" => 4, "capacite" => 5, "categorie" => 6, "cp" => 7, "nom_reel" => 8, "supprimer" => 9, "prix" => 10, "promotion_id" => 11, "salle_id" => 12);
                foreach ($dataPanier as $k => $v)
                {
                    foreach ($v as $key => $values)
                    {
                        if (array_key_exists($key, $ordreAffichage))
                        {
                            $dataPanierTrie[$k][$ordreAffichage[$key]] = $values;
                        }
                    }
                    $dataPanierTrie[$k][9] = $v->id;
                    ksort($dataPanierTrie[$k]);
                    $dataPanierTrie[$k] = (object) array_combine(array_flip($ordreAffichage), $dataPanierTrie[$k]);
                }
                $_SESSION['panier']['article'] = $dataPanierTrie;
                $result = "";
                $sousTotal = 0;
                foreach ($_SESSION['panier']['article'] as $k => $article)
                {
                    ($k % 2 === 0) ? $bg = "#f8f8f8" : $bg = "#fff";
                    $result .= '<tr style="background:' . $bg . '">';
                    foreach ($article as $key => $values)
                    {
                        if ($key == "photo")
                        {
                            // cas image
                            $result .= "<td><a class=\"imgSalle link-grey\"  rel=\"lightbox\" title=\"\" href=\"lokisalle/images/salles/miniature/grande/" . $article->photo . "\"><span class=\"glyphicon glyphicon-picture\"></span></a></td>";
                        }
                        elseif ($key == "supprimer")
                        {
                            // cas supprimer
                            $result .= '<td><a class="delete" data="de supprimer un produit de votre panier" href="index.php?controller=produit&action=supprimerProduitPanier&id=' . $article->supprimer . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
                        }
                        elseif ($key == "prix")
                        {
                            // cas prix
                            $sousTotal += $values;
                            $result .= "<td>" . $values . "€</td>";
                        }
                        elseif ($key == "promotion_id" || $key == "salle_id")
                        {
                            // Cas de promotion_id et salle id que nous ne voulons pas afficher!!
                        }
                        else
                        {
                            // autres cas
                            $result .= "<td>".$this->cleanAntiSlash($values)."</td>";
                        }
                    }
                    $result .= "</tr>";
                }
                $this->sousTotal = $sousTotal;
                $result .= "<tr style=\"font-weight:bold\"><td colspan=\"10\" style=\"text-align:right;\">Sous-total HT</td><td>" . $this->sousTotal . "€</td></tr>";
                $result .= "<tr style=\"font-weight:bold\"><td colspan=\"10\" style=\"text-align:right\">Réduction HT</td><td>" . $this->getPromotionTotal() . "€</td></tr>";
                $result .= "<tr style=\"font-weight:bold\"><td colspan=\"10\" style=\"text-align:right\">TVA (20%)</td><td>" . (($this->sousTotal - $this->getPromotionTotal()) * 0.2) . "€</td></tr>";
                $result .= "<tr class=\"total\" style=\"background:#FFF2E0\"><td colspan=\"10\" style=\"text-align:right\">Montant TTC</td><td>" . (($this->sousTotal - $this->getPromotionTotal()) * 1.2) . "€</td></tr>";
            }
            $this->updateBottonPanier();
            $_SESSION['panier']['boutton'] = array("nombreProduit" => $this->nbProduit, "montant" => ($this->sousTotal - $this->getPromotionTotal()) * 1.2);
        }

        (!empty($message) && $this->basketIsNotNull()) ? $alert = $message : true;
        // On affiche
        $this->render("produit/panier.php", array(
            "title" => "Votre panier",
            "dataReponse" => array(),
            "connected" => $connected,
            "produits" => $result,
            "alert" => $alert
        ));
    }

    /*
     * Permet de mettre à jour le boutton du panier.
     */

    public function updateBottonPanier()
    {
        if ($this->basketIsNotNull())
        {
            $montant = 0;
            $this->nbProduit = count($_SESSION['panier']['article']);
            foreach ($_SESSION['panier']['article'] as $values)
            {
                $montant += $values->prix;
            }
            return $this->sousTotal = $montant;
        }
    }

    /*
     * Permet de savoir si le panier est vide.
     * @return (boolean)
     */

    public function basketIsNotNull()
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : true;
        if(isset($_SESSION['panier']['article']) && !empty($_SESSION['panier']['article']))
        {
            $data = true;
        }
        else
        {
             $request->getParameters("SESSION");
             $data = false;
        }
        return $data;
    }

    /*
     * Permet d'ajouter un produit au panier.
     * @ parameters (array) $arg[0] => id du produit 
     */

    public function ajouterProduitPanier($arg = array())
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : true;
        (!empty($arg)) ? $id = $arg[0] : $id = false;
        settype($id, "integer");
        if ($id)
        {
            // On initialise le panier
            (!$this->basketIsNotNull()) ? $_SESSION['panier']['article'] = array() : true;
            // Si on a pas encore ajouté de produit
            if (count($_SESSION['panier']['article']) > 0)
            {
                // On va verifier qu'il na pas déjà été ajouté
                foreach ($_SESSION['panier']['article'] as $values)
                {
                    if ($values->id == $id)
                    {
                        $existe = true;
                        break;
                    }
                    else
                    {
                        $existe = false;
                    }
                }

                if (!$existe)
                {
                    $produit = $this->getRepository("produit");
                    $produits = $produit->findProduitBy(array("p.etat = '0'", "p.id = $id", "p.date_arrivee >= NOW()"));
                    if (count($produits) === 1)
                    {
                        array_push($_SESSION['panier']['article'], $produits['0']);
                        $alert = array();
                    }
                    else
                    {
                        $alert = array("danger", "Le produit que vous voulez mettre dans le panier n'existe pas!");
                    }
                }
                else
                {
                    $alert = array("danger", "Le produit que vous voulez mettre dans le panier a déjà été ajouté!");
                }
            }
            else
            {
                $produit = $this->getRepository("produit");
                $produits = $produit->findProduitBy(array("p.etat = '0'", "p.id = $id", "p.date_arrivee >= NOW()"));
                if (count($produits) === 1)
                {
                    array_push($_SESSION['panier']['article'], $produits['0']);
                    $alert = array();
                }
                else
                {
                    $alert = array("danger", "Le produit que vous voulez mettre dans le panier n'existe pas!");
                }
            }
        }
        else
        {
            $alert = array("danger", "Le produit que vous voulez mettre dans le panier n'existe pas!");
        }
        $this->updateBottonPanier();
        $this->panier($alert);
    }

    /*
     * Permet de supprimer un produit du panier.
     * @ parameters (array)$arg => id du produit && (boolean)$display => permet d'afficher ou pas le message d'alert
     */

    public function supprimerProduitPanier($arg = array(), $display = true)
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->acccesDenied() : true;
        $alert = array("danger", "Le produit que vous voulez supprimer de votre panier n'existe pas!");
        if (count($arg) === 1)
        {
            $id = $arg[0];
            settype($id, "integer");
            if ($this->basketIsNotNull())
            {
                foreach ($_SESSION['panier']['article'] as $key => $value)
                {
                    if ($value->id == $id)
                    {
                        unset($_SESSION['panier']['article'][$key]);
                        if (isset($_SESSION['panier']['promotion']) && count($_SESSION['panier']['article']) > 0)
                        {
                            foreach ($_SESSION['panier']['promotion'] as $k => $v)
                            {
                                if ($v->id == $id)
                                {
                                    unset($_SESSION['panier']['promotion'][$k]);
                                }
                            }
                        }
                        $alert = array("success", "Le produit a été supprimé!");
                        $this->getPromotionTotal();
                    }
                }
            }
        }
        $this->updateBottonPanier();
        return $display ? $this->panier($alert) : $alert;
    }

    /*
     * Permet de vider le panier.
     * @parameters (boolean)$display => permet d'afficher ou pas le message d'alert
     *
     */

    public function viderPanier($display = true)
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : true;
        unset($_SESSION["panier"]);
        $this->updateBottonPanier();
        if ($display)
        {
            $this->panier();
        }
    }

    /*
     * Permet de récupérer l'id d'une promotion. 
     * Elle permet par la suite de voir si un produit du panier à la meêne id promotion et de lui aplliquer la reduction (cf . promotionPanier())
     * @parameters (boolean)$display => permet d'afficher ou pas le message d'alert
     *
     */

    public function getIdPromotion()
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : true;
        $dataPost = $request->getParameters('POST');
        if (isset($dataPost['code_promo']) && !empty($dataPost['code_promo']))
        {
            $produit = $this->getRepository("produit");
            $produits = $produit->getPromotionBy(array("code_promo = '$dataPost[code_promo]'"));
            if (count($produits) === 1)
            {
                // Le code promo existe
                return $produits[0];
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Permet d'aplliquer une réduction sur un produit du panier. 
     */

    public function promotionPanier()
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : true;
        $request->getParameters('SESSION');
        $_SESSION['panier']['article'] = (isset($_SESSION['panier']['article'])) ? $_SESSION['panier']['article'] : array();
        $_SESSION['panier']['promotion'] = (isset($_SESSION['panier']['promotion'])) ? $_SESSION['panier']['promotion'] : array();
        $infoPromo = $this->getIdPromotion();
        if ($infoPromo)
        {

            foreach ($_SESSION['panier']['article'] as $key => $dataArticles)
            {
                $match = 0;
                $alert = array();

                if ($dataArticles->promotion_id == $infoPromo->id)
                {
                    // le code promo correspond au produit tu recupères l'id et la réduction en €
                    $row = $key;
                    $id = $dataArticles->id;
                    $prix = $dataArticles->prix;
                    $reduction = (int) $dataArticles->prix * (int) $infoPromo->reduction / 100;
                }


                // Tu verifies si le panier est vide ou pas!

                (isset($_SESSION['panier']['promotion'])) ? true : $_SESSION['panier']['promotion'] = array();
                if (isset($id) && isset($reduction))
                {
                    if (count($_SESSION['panier']['promotion']) > 0)
                    {
                        // On vérifie que le code n'a pas été déjà utilisé
                        foreach ($_SESSION['panier']['promotion'] as $dataPromotion)
                        {
                            if ($dataPromotion->id == $id)
                            {
                                $match++;
                            }
                        }
                        if (!$match)
                        {
                            $prixTTC = ($prix - $reduction) * 1.2;
                            array_push($_SESSION['panier']['promotion'], (object) array("id" => $dataArticles->id, "reduction" => $reduction, "prix" => $prixTTC));
                            $alert = array("success", "La réduction a été appliquée!");
                        }
                        else
                        {
                            $alert = array("danger", "La réduction a déjà été appliquée!");
                        }
                    } // On insère dans panier=>promotion car promotion vide
                    else
                    {
                        $prixTTC = ($prix - $reduction) * 1.2;
                        array_push($_SESSION['panier']['promotion'], (object) array("id" => $dataArticles->id, "reduction" => $reduction, "prix" => $prixTTC));
                        $alert = array("success", "La réduction a été appliquée!");
                    }
                }
                else
                {
                    $alert = array("danger", "Le code promotionnel n'est pas valide pour le ou les produits");
                }
            }
        }
        else
        {
            $alert = array("danger", "Ce code promo ne marche pas avec ce produit!");
        }
        $this->updateBottonPanier();
        $this->panier($alert);
    }

    /*
     * Permet de récupérer le montant total des reductions. 
     * @ return (integer)
     *
     */

    public function getPromotionTotal()
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->acccesDenied() : true;
        if (isset($_SESSION['panier']['promotion']) && count($_SESSION['panier']['promotion']) > 0)
        {
            $reduction = 0;
            foreach ($_SESSION['panier']['promotion'] as $value)
            {
                $reduction += (float) $value->reduction;
            }
            return $reduction;
        }
        else
        {
            return 0;
        }
    }

    /*
     * Permet le paiement du panier(finaliser la commande). 
     */

    public function paiement()
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->acccesDenied() : true;
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            if (isset($dataPost['cgv']) && $dataPost['cgv'] = "on")
            {
                // Vérifier si le ou les produit(s) existe toujours
                $produit = $this->getRepository("produit");
                $dataSession = $request->getParameters("SESSION");
                if (isset($dataSession['panier']['article']) && count($dataSession['panier']['article']) > 0)
                {
                    foreach ($dataSession['panier']['article'] as $values)
                    {
                        $produits = $produit->findProduitBy(array("p.id = '" . $values->id . "'", "p.etat = '1'", "p.date_arrivee >= NOW()"));
                        $ProductReserved = false;
                        $NbProduit = "";
                        if ($produits)
                        {
                            $this->supprimerProduitPanier((array) $values->id, false);
                            $NbProduit .= $values->id;
                            $ProductReserved = true;
                        }
                    }

                    if (!$ProductReserved)
                    {
                        $alert = array("success", "<strong>Merci pour votre confiance!</strong>  Vous venez de recevoir un mail récapitulatif de votre commande.");
                        foreach ($dataSession['panier']['article'] as $article)
                        {
                            $dateArrivee = new \DateTime($article->date_arrivee);
                            $dateArrivee = date_format($dateArrivee, 'Y-m-d H:i:s');
                            $dateDepart = new \DateTime($article->date_depart);
                            $dateDepart = date_format($dateDepart, 'Y-m-d H:i:s');

                            $dataSave = array("date_arrivee" => $dateArrivee, "date_depart" => $dateDepart, "etat" => "1", "salle_id" => $article->salle_id, "id" => $article->id);
                            $produits = $produit->saveProduit($dataSave);
                        }
                        $data = array("montant" => ($this->updateBottonPanier() - $this->getPromotionTotal()) * 1.2, "date" => date("Y-m-d H:i:s"), "statut_commande" => "En attente de paiement", "membre_id" => $dataSession['Auth']->id);
                        $articlesId = array();
                        foreach ($dataSession['panier']['article'] as $values)
                        {
                            if (isset($dataSession['panier']['promotion']) && !empty($dataSession['panier']['promotion']))
                            {
                                foreach ($dataSession['panier']['promotion'] as $val)
                                {
                                    $prix = ($val->id == $values->id) ? $val->prix : $values->prix * 1.2;
                                }
                            }
                            else
                            {
                                $prix = $values->prix * 1.2;
                            }

                            $articlesId += array($values->id => array("prix" => $prix, "salle_id" => $values->salle_id));
                        }
                        $numCommande = $produit->ajouterCommande($data, $articlesId);
                        $_SESSION["panier"]["promotion"] = array();
                        $this->felicitation($alert, $numCommande, true);
                        exit();
                    }
                    else
                    {
                        $alert = array("danger", "<strong>Désolé le produit" . $NbProduit . " n'existe plus!</strong>  Il vient d'être enlevé de votre panier.");
                    }
                }
                else
                {
                    $alert = array();
                }
            }
            else
            {
                $alert = array("danger", "Vous devez accepter les conditions générales de vente avant de payer!");
            }

            $this->updateBottonPanier();
            $this->panier($alert);
        }
    }

    /*
     * Permet l'affiche la page de félicitaiton après le paiement . 
     * @parameters (array) $message&& (boolean)$paiementValid 
     *
     */

    public function felicitation($alertMessage, $numCommande, $paiementValid = false)
    {
        $request = new Request();
        (!$request->isConnected()) ? $request->accessDenied() : $connected = true;
        if ($paiementValid)
        {
            if ($this->basketIsNotNull())
            {
                $result = $messageBody = "";
                $sousTotal = 0;
                foreach ($_SESSION['panier']['article'] as $k => $article)
                {
                    ($k % 2 === 0) ? $bg = "#f8f8f8" : $bg = "#fff";
                    $result .= '<tr style="background:' . $bg . '">';
                    foreach ($article as $key => $values)
                    {
                        if ($key == "photo")
                        {
                            // cas image
                            $result .= "<td class=\"tdImg\"><a  class=\"imgSalle link-grey\"  rel=\"lightbox\" title=\"\" href=\"" . WEBROOT . "web/lokisalle/images/salles/miniature/grande/" . $article->photo . "\"><span style=\"width:15px;height:15px\" class=\"glyphicon glyphicon-picture\"></span></a></td>";
                        }
                        elseif ($key == "prix")
                        {
                            // cas prix
                            $sousTotal += $values;
                            $messageBody .= "<td>" . $values . "€</td>";
                            $result .= "<td>" . $values . "€</td>";
                        }
                        elseif ($key == "promotion_id" || $key == "supprimer" || $key == "salle_id")
                        {
                            // cas où on ne veut pas afficher ces valeurs
                        }
                        else
                        {
                            // autres cas
                            $messageBody .= "<td>".$this->cleanAntiSlash($values)."</td>";
                            $result .= "<td>".$this->cleanAntiSlash($values)."</td>";
                        }
                    }
                    $result .= "</tr>";
                }
                $result .= "<tr style=\"font-weight:bold\"><td colspan=\"9\" style=\"text-align:right;\">Sous-total HT</td><td>" . $sousTotal . "€</td></tr>";
                $result .= "<tr style=\"font-weight:bold\"><td colspan=\"9\" style=\"text-align:right\">Réduction HT</td><td>" . $this->getPromotionTotal() . "€</td></tr>";
                $result .= "<tr style=\"font-weight:bold\"><td colspan=\"9\" style=\"text-align:right\">TVA (20%)</td><td>" . (($sousTotal - $this->getPromotionTotal()) * 0.2) . "€</td></tr>";
                $result .= "<tr class=\"total\" style=\"background:#FFF2E0\"><td colspan=\"9\" style=\"text-align:right\">Montant TTC</td><td>" . (($sousTotal - $this->getPromotionTotal()) * 1.2) . "€</td></tr>";

                
                $messageBody .= "<tr style=\"font-weight:bold\"><td colspan=\"8\" style=\"text-align:right;\">Sous-total HT</td><td>" . $sousTotal . "€</td></tr>";
                $messageBody .= "<tr style=\"font-weight:bold\"><td colspan=\"8\" style=\"text-align:right\">Réduction HT</td><td>" . $this->getPromotionTotal() . "€</td></tr>";
                $messageBody .= "<tr style=\"font-weight:bold\"><td colspan=\"8\" style=\"text-align:right\">TVA (20%)</td><td>" . (($sousTotal - $this->getPromotionTotal()) * 0.2) . "€</td></tr>";
                $messageBody .= "<tr class=\"total\" style=\"background:#FFF2E0\"><td colspan=\"8\" style=\"text-align:right\">Montant TTC</td><td>" . (($sousTotal - $this->getPromotionTotal()) * 1.2) . "€</td></tr>";
                
                $this->viderPanier(false);
                // On envoi un mail////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                $message = '<h2>Votre commande LOKISALLE: </h2><br/><p><strong>Référence de votre commande : </strong>' . date("Ymd") . '-' . $numCommande . '</p> <p><strong>Date: </strong>' . date('d-m-Y H:i') . '</p><br/>';
                $message .= '<table border="1" id="affiche" class="table table-hover" style="text-align:center !important; border-collapse: collapse">';
                $message .= '<thead><tr style="background:#B80000;color:#fff">';
                $message .= '<th>#</th>';
                $message .= '<th>Salle</th>';
                $message .= '<th>Date d\'arrivée</th>';
                $message .= '<th>Date de départ</th>';
                $message .= '<th>Capacité</th>';
                $message .= '<th>Catégorie</th>';
                $message .= '<th>Code postal</th>';
                $message .= '<th>Ville </th>';
                $message .= '<th>Prix HT </th>';
                $message .= '</tr></thead>';
                $message .= $messageBody;
                $message .= '</table>';
                $message .= '<br/><p><strong>LOKISANNE</strong><br/> 11 rue de paris<br/> 75001 Paris france<br/> Tél: 01 02 03 04 05 <br/> Email: contact@lokisalle.fr</p>';
                $destinateur = array(ucfirst($_SESSION['Auth']->prenom) . " " . $_SESSION['Auth']->nom => $_SESSION['Auth']->email);
                $expediteur = array("Lokisalle" => "commercial@lokisalle.fr");
                $sujet = "Récapitulatif de votre commande";
                $email = new SendEmail($expediteur, $destinateur, $sujet, $message);
                $email->send();

                // On affiche
                $this->render("produit/felicitation.php", array(
                    "title" => "félicitation",
                    "dataReponse" => array(),
                    "connected" => $connected,
                    "produits" => $result,
                    "numCommande" => $numCommande,
                    "alert" => $alertMessage
                ));
                exit();
            }
            else
            {
                $this->accueil();
            }
        }
        else
        {
            $this->accueil();
            exit();
        }
    }

}

?>