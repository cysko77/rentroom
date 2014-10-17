<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;
use BackOffice\Repository\avisRepository;
use BackOffice\Repository\commandeRepository;
use BackOffice\Repository\details_commandeRepository;

class produitRepository extends EntityRepository
{

    /**
     * Cette méthode permet de retourner les produits en fonction de parameters passés dans la methode
     * @parameters  (array)$fil && (array)$tri  
     * $fil = filtre les données et $tri = trie les données retournées
     * @return	array
     */
    public function findProduitBy($fil = array(), $tri = array())
    {
        $fields = array("p.id", "s.titre", "s.photo", "DATE_FORMAT(p.date_arrivee,'%d-%m-%Y') as date_arrivee", "DATE_FORMAT(p.date_depart,'%d-%m-%Y') as date_depart", "p.prix", "s.capacite", "s.categorie", "s.cp", "v.nom_reel", "p.salle_id", "p.promotion_id", "p.etat", "s.description", "ROUND(AVG(a.note)) as moyenne_avis", "COUNT(a.id) as nombre_avis ");
        $tables = array("produit p");
        $leftJoin = array("salle s" => "p.salle_id = s.id", "ville_france v" => "v.id = s.ville_france_id", "avis a" => "s.id = a.salle_id");
        $filtre = array();
        $tr = array("GROUP BY"=> "p.id");
        $tri =  $tr+$tri;
        if (!empty($fil))
        {
            foreach ($fil as $key => $values)
            {
                array_push($filtre, "$values");
            }
        }
        $args = array("fields" => $fields, "tables" => $tables, "leftJoin" => $leftJoin, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }

    /**
     * Cette méthode permet de savoir si le produit n'est pas lié à une salle
     * @parameters  (integer)$id  
     * @return	array
     */
    public function produitDontHaveSalle($id)
    {
        $fields = array("id");
        $tables = array("produit");
        $filtre = array("((date_depart > NOW() AND date_arrivee < NOW()) OR (date_arrivee > NOW()))", "salle_id = $id");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre);
        return $this->find($args);
    }

    /**
     * Permet de récupérer les détails d'un produit && ces commentaires
     * @parameters  (integer)$id  
     * @return	(array)
     */
    public function findDetailsProduit($id,$tri = null)
    {

        $fields = array("p.id", "ROUND(AVG(a.note)) as moyenne_avis", "COUNT(a.id) as nombre_avis ", "s.titre", "s.photo", "DATE_FORMAT(p.date_arrivee,'%d-%m-%Y') as date_arrivee", "DATE_FORMAT(p.date_depart,'%d-%m-%Y') as date_depart", "p.prix", "s.capacite", "s.categorie", "s.description", "s.adresse", "v.nom_reel", "s.cp", "s.long_deg", "s.lat_deg", "p.salle_id");
        $tables = array("produit p");
        $leftJoin = array("salle s" => "p.salle_id = s.id", "ville_france v" => "v.id = s.ville_france_id", "avis a" => "a.salle_id = s.id");
        $filtre = array("p.etat = '0'", "p.date_arrivee >= NOW()", "p.id = $id");
        $args = array("fields" => $fields, "tables" => $tables, "leftJoin" => $leftJoin, "filtre" => $filtre, "tri" => array());
        // On recupère les données du produit 
        $result['produit'] = $this->find($args);

        // Si le produit n'existe pas on retour un message d'erreur
        if ($result['produit'][0]->id === null)
        {
            return array('produit' => false, 'avis' => null);
            exit();
        }
        else
        {
            // On récupère les avis relative au produit 
            $avis = new avisRepository();
            $salle_id = $result['produit'][0]->salle_id;
            $startTri = ($tri) ? $tri : 0;
            $tri = array("ORDER BY" => "date DESC", "LIMIT" => "$startTri,4");
            $filtre = array("salle_id = $salle_id");
            $result['avis'] = $avis->findAvis($filtre, $tri);
            $result['totalAvis'] = count($avis->findAvis($filtre, $tri = array()));
        }
        return $result;
    }

    /**
     * Permet de savoir si un produit est valide (Si les dates du produit ne se chevauchent pas avec un autre produit) 
     * Cette méthode est appelée juste avant de sauver les données du produit.
     * @parameters  (array)$dataSave 
     * @return	(boolean)
     */
    public function produitIsValide($dataSave)
    {
        
        $date = new \DateTime($dataSave[date_arrivee]);
        $date = $date->format("Y-m-d");
        $dataSave[date_arrivee] = $date;
        $date = new \DateTime($dataSave[date_depart]);
        $date = $date->format("Y-m-d");
        $dataSave[date_depart] = $date;
        // we check if date is not used by another product
        $fields = array("p.id", "p.date_arrivee", "p.date_depart");
        $tables = array("produit p", "salle s");
        $filtre = array("p.salle_id = s.id",  "p.date_arrivee > NOW()", "p.salle_id = '$dataSave[salle_id]'","((p.date_depart BETWEEN '$dataSave[date_arrivee]' AND '$dataSave[date_depart]') OR (p.date_arrivee BETWEEN '$dataSave[date_arrivee]' AND '$dataSave[date_depart]'))");
        // If to update product, we add filter in sql.    
        if (isset($dataSave['id']))
        {
            array_push($filtre, "p.id != '$dataSave[id]'");
        }
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => array());
        $result = $this->find($args);
        if (count($result) > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /*
     * Cette méthode sert à savoir si un produit peut être supprimer sans compromettre le bon fonctionnement de l'appli.
     * Cette méthode est appelé juste avant de supprimer un produit
     * @parameter (integer)$id
     * @return (boolean)
     *
     */

    public function ProduitIsSupprimable($id)
    {
        $fields = array("DATE_FORMAT(p.date_depart,'%d/%m/%Y à %Hh%im')as datedepart");
        $tables = array("produit p");
        $filtre = array("(p.etat = '0' OR (p.etat = '1' AND date_depart < NOW()))", "p.id = $id");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => array());
        $result = $this->find($args);
        if (count($result) == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
     * Permet de récupèrer tous les promotions
     * @return (array)
     *
     */

    public function getPromotion()
    {
        $promotion = new promotionRepository();
        return $promotion->findAll();
    }

    /*
     * Permet de récupèrer les promotions suivant les données passés
     * @parameters (array) $arg
     * @return (array)
     *
     */

    public function getPromotionBy($arg)
    {
        $promotion = new promotionRepository();
        return $promotion->findPromotionBy($arg);
    }

    /*
     * Permet de retourner les resultats d'une recherche sur un salle. Utilisée en ajax
     * @parameters (array) $arg
     * @return (array)
     *
     */

    public function searchSalle($arg)
    {
        $salle = new salleRepository();
        $filtre = array("CONCAT(v.pays,' ',v.nom_reel,' ',s.adresse,' ',s.cp,' ',s.titre,' ',s.capacite,' ',s.categorie) LIKE '%$arg%'");
        $result = $salle->findSalleBy(array(), $filtre);
        if (count($result))
        {
            return array("reponse" => "success", "result" => $result);
        }
        else
        {
            return array("reponse" => "error");
        }
    }

    /*
     * Permet de recupérer tous les salles. Utilisée quant javascript est désactivé.
     * @return (array)
     *
     */

    public function getSalle()
    {
        $salle = new salleRepository();
        $fields = array("CONCAT(v.pays, ' - ', v.nom_reel,' - ', s.adresse, ' - ',s.cp, ' - ',s.titre, ' - ', s.capacite, ' - ', s.categorie) as salle");
        return $salle->findSalleBy($fields);
    }

    /*
     * Permet de sauver ou modifier un produit
     * @parameters (array) $data
     * @return (array)
     *
     */

    public function saveProduit($data)
    {
        if ($this->produitIsValide($data))
        {

            $reponse = $this->save($data);
            if (isset($data['id']))
            {
                $save = "modifié";
            }
            else
            {
                $save = "enregistré";
            }

            if ($reponse)
            {
                // Le produit a été sauvé!
                $alert = array("success", "Le produit a été $save!");
                $data = array();
            }
            else
            {
                //Le produit n'a pas été sauvé!
                if($save ==="enregistré")
                {
                    $alert = array("danger", "Le produit n'a pas été enregistré! Vérifiez votre formulaire!");
                }
                else
                {
                    $alert = array("warning", "Le produit n'a pas été modifié, car vous n'avez pas changée les données du produit!");
                } 
            }
        }
        else
        {
            $date_arrivee = new \DateTime($data['date_arrivee']);
            $date_depart = new \DateTime($data['date_depart']);
            $date_arrivee = str_replace("-", "/", $date_arrivee->format('d-m-Y'));
            $date_depart = str_replace("-", "/", $date_depart->format('d-m-Y'));
            $alert = array("danger", "La salle ne peut pas être réservée du $date_arrivee au $date_depart car les dates se chevauchent avec d'autres produits! Veuillez changer les dates!");
            $data['date_arrivee'] = false;
            $data['date_depart'] = false;
        }
        return array("reponse" => $data, "alert" => $alert);
    }

    /*
     * Permet de supprimer un produit
     * @parameters (integer) $id
     * @return (array)
     *
     */

    public function supprimerProduit($id)
    {
        if ($this->produitIsSupprimable($id))
        {
            if ($this->delete($id))
            {
                return array("success", "Le produit a été supprimé!");
            }
            else
            {
                return array("danger", "Il n'a pas été supprimé car il n'existe pas!");
            }
        }
        else
        {
            $date = $this->produitIsSupprimable($id);
            return array("danger", "Le produit ne peut pas être supprimé car il est actuellement réservé par un client!");
        }
    }

    /*
     * Permet de sauver une commande et de recupérer son numéro
     * @parameters ($dataSave) $id
     * @return (integer)
     *
     */

    public function ajouterCommande($dataSave, $articlesId)
    {
        $commande = new commandeRepository();
        $reponse = $commande->saveCommande($dataSave);
        $details = new details_commandeRepository();
        if ($reponse['reponse'])
        {
            foreach ($articlesId as $produitId => $produit)
            {
                $data = array("commande_id" => $reponse['reponse'], "produit_id" => $produitId, "prix" => $produit["prix"], "salle_id" => $produit["salle_id"]);
                $details->saveDetailsCommande($data);
            }
            return $reponse['reponse'];
        }
    }

    public function saveAvis($dataSave)
    {
        $avis= new avisRepository();
        return $avis->ajouterAvis($dataSave);
    }
}

?>