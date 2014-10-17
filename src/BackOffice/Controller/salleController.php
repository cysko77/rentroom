<?php

namespace BackOffice\Controller;

use Controller\Controller;
use HttpRequest\Request;
use Upload\qqFileUploader;

class salleController extends Controller
{
    /*
     * Permet d'ajouter une salle
     */

    public function ajouter()
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $salle = $this->getRepository('salle');
        // Si le formulaire est soumis
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            // Si fichier uploder
            if ($request->isUploadFile())
            {
                $this->uploadImg();
            }
            $dataPlus = array();
            $dataReponse = $request->checkParameters("salle", $dataPost, $dataPlus);
            
            // On vérifie s'il ya des erreurs dans les données envoyées
            if (!in_array(false, $dataReponse, true))
            {
                // On vérifie la date depart > date arrivée
                // On tente de sauvegarde  les données.
                $dataReponse['description'] = $dataReponse['description'];
                ($dataReponse["photo"] === null) ? $dataReponse["photo"] = "noImg.jpg" : true;
                $dataSave = $dataReponse + $dataPlus;
                // On récupère la reponse 
                $reponse = $salle->saveSalle($dataSave);
                $alert = $reponse['alert'];
                $dataReponse = $reponse['reponse'];
                $request->redirection("salle","afficher",$alert);
                exit();
            }
            else
            {
                $alert = array("danger", "La salle n'a pas été enregistrée! \n Vérifiez votre formulaire!");
                if (isset($dataReponse['cp']) && ($dataReponse['cp']))
                {
                    $ville = $salle->getVille(array($dataReponse['cp']));
                }
                else
                {
                    $ville = array();
                }
            }
        }
        else
        {
            //Formulaire non soumis
            $dataReponse = array();
            $alert = array();
            $ville = array();
        }

        // On affiche les resultats
        $this->render("salle/ajouter.php", array(
            "title" => "Ajouter une salle",
            "dataReponse" => $dataReponse,
            "ville" => $ville,
            "alert" => $alert
        ));
    }

    /*
     * Permet d'afficher la liste des salles.
     * @parameters (array) $arg  && (array) $alert
     *
     */

    public function afficher($arg = array(), $message = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $triAffichage = array("id" => "asc", "titre" => "asc", "description" => "asc", "photo" => "asc", "adresse" => "asc", "cp" => "asc", "nom_reel" => "asc", "capacite" => "asc", "categorie" => "asc");
        $salle = $this->getRepository('salle');
        $filtre = array();
        $tri = $this->triAffichage($arg, $triAffichage);
        $salles = $salle->findSalleBy($field = array(), $filtre, $tri);
        $salle->filtreText($salles, "description", 37);
        $result = "";
        foreach ($salles as $k => $value)
        {
            $bg = ($k % 2 === 0) ? "#f8f8f8" : "#fff";
            $result .= '<tr style="background:' . $bg . '">';
            foreach ($value as $key => $val)
            {
                if (array_key_exists($key, $triAffichage))
                {
                    if($key !== "photo")
                    {
                        $result .= "<td>".$this->cleanAntiSlash($val)."</td>";
                    }
                    else
                    {
                        $result .= "<td><a class=\"imgSalle link-grey\"  rel=\"lightbox\" title=\"\" href=\"lokisalle/images/salles/miniature/grande/" . $value->photo . "\"><span class=\"glyphicon glyphicon-picture\"></span></a></td>";
                    }
                }
            }
            $result .= '<td><a href="index.php?controller=salle&action=modifier&id=' . $value->id . '" style="color:orange"><span class="glyphicon glyphicon-remove"></span></a></td>';
            $result .= '<td><a class="delete" data="de supprimer une salle" href="index.php?controller=salle&action=supprimer&id=' . $value->id . '" style="color:red"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $result .= "</tr>";
        }

        $nbreSalle = count($salle->findSalleBy($filtre));
        $url = "index.php?controller=salle&action=afficher&triby=$this->orderBy&tri=$this->typeTri";
        $pagination = $this->pagination($nbreSalle,$url);
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
            $this->render("salle/afficher.php", array(
                "title" => "Tous les salles",
                "salles" => $result,
                "alert" => $alert,
                "triAffichage" => $triAffichage,
                "pagination" => $pagination
            ));
        }
    }

    /*
     * Permet d'Uploader la photo de la salle.
     */

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
            $imgResult = $uploader->rezise(255, 255, 255, $uploadDir, INDEXROOT . "/lokisalle/images/salles/miniature/");
            $uploader->rezise(780, 590, 590, $uploadDir, INDEXROOT . "/lokisalle/images/salles/miniature/grande/");
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

    /*
     * Permet de modifier une salle.
     * @parameters (array) $arg => données de la salle
     *
     */

    public function modifier($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $salle = $this->getRepository('salle');
        $id = (!empty($arg)) ?  $arg[0] : false;
        if ($request->getMethod() == "GET")
        {
            // Si l'id est correctement renseigné dans l'url au affiche les données dans un formulaire 
            settype($id, "integer");
            if ($id)
            {
                $filtre = array("s.id = $id");
                $dataReponse = $salle->findSalleBy($field = array(), $filtre);
                // S'il y a un résultat
                if (!empty($dataReponse))
                {
                    $alert = array();
                }
                else
                {
                    $alert = array("danger", "La salle que vous voulez modifier n'est pas modifiable!");
                    $this->afficher(array(), $alert);
                    exit();
                }
            }
            else
            {
                // Redirection vers les produits
                $dataReponse = array('salle' => false,);
                $alert = array("danger", "La salle que vous voulez modifier n'existe pas!");
                $this->afficher(array(), $alert);
                exit();
            }
        }
        // Si le formulaire est soumis
        if ($request->getMethod() == "POST")
        {
            $dataPost = $request->getParameters("POST");
            // Si fichier uploder
            if ($request->isUploadFile())
            {
                $this->uploadImg();
            }
            $dataPlus = array("id" => $id);
            $dataReponse = $request->checkParameters("salle", $dataPost, $dataPlus);
            // On vérifie s'il ya des erreurs dans les données envoyées
            if (!in_array(false, $dataReponse, true))
            {
                // On vérifie la date depart > date arrivée
                // On tente de sauvegarde  les données.
                $dataReponse['description'] = $dataReponse['description'];
                $dataSave = $dataReponse + $dataPlus;
                // On récupère la reponse 
                $reponse = $salle->saveSalle($dataSave);
                $alert = $reponse['alert'];
                $dataReponse = $reponse['reponse'];
                $request->redirection("salle","afficher",$alert);
                exit();
            }
            else
            {
                $alert = array("danger", "La salle n'a pas été modifiée! \n Vérifiez votre formulaire!");
            }
        }

        if (isset($dataReponse[0]->cp) && ($dataReponse[0]->cp))
        {
            $ville = $salle->getVille(array($dataReponse[0]->cp));
        }
        else
        {
            $ville = array();
        }

        // On affiche les resultats
        $this->render("salle/modifier.php", array(
            "title" => "Modifier une salle",
            "dataReponse" => $dataReponse,
            "ville" => $ville,
            "alert" => $alert
        ));
    }

    /*
     * Permet d'afficher la liste des salles.
     * @parameters (array) $arg  avec $arg[0] => id de la salle
     *
     */

    public function supprimer($arg = array())
    {
        $request = new Request();
        (!$request->isAdmin()) ? $request->accessDenied() : true;
        $salle = $this->getRepository('salle');
        $alert = $salle->supprimerSalle($arg[0]);
        $request->redirection("salle","afficher",$alert);
        exit();
    }

}

?>