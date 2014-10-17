<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;
use BackOffice\Repository\details_commandeRepository;
use BackOffice\Repository\produitRepository;

class commandeRepository extends EntityRepository
{

    public function findAllCommande()
    {
        return $this->findAll();
    }

    public function findCommandeBy($fil = array(), $tri = array())
    {
        $fields = array("id", "DATE_FORMAT(date,'%d-%m-%Y %H:%i') as date", "montant", "statut_commande", "membre_id");
        $tables = array("commande");
        $filtre = array();
        if (!empty($fil))
        {
            foreach ($fil as $key => $values)
            {
                array_push($filtre, "$values");
            }
        }

        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }
    
    
    /**
     * Permet de retourner le top 5 des membres qui achète le plus (quantité)
     * @return	array
    */
    public function Top5MembresQuantite()
    {
        $fields = array("m.id", "COUNT(*) as Nombre_achat", "m.pseudo", "m.prenom", "m.nom","m.adresse", "m.cp", "v.nom_reel");
        $tables = array("commande c","membre m","ville_france v");
        $filtre = array("c.membre_id  = m.id", "m.ville_france_id = v.id");
        $tri = array("GROUP BY"=> "c.membre_id","ORDER BY Nombre_achat" => "DESC", "LIMIT" => "0,5");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }
    
    /**
     * Permet de retourner le top 5 des membres qui achète le plus (Prix)
     * @return	array
    */
    public function Top5MembresPrix()
    {
        $fields = array("m.id", "ROUND(SUM(dc.prix)) as Somme_depense", "m.pseudo", "m.prenom", "m.nom","m.adresse", "m.cp", "v.nom_reel");
        $tables = array("commande c","membre m","ville_france v","details_commande dc");
        $filtre = array("c.membre_id  = m.id", "m.ville_france_id = v.id", "c.id = dc.commande_id");
        $tri = array("GROUP BY"=> "c.membre_id","ORDER BY Somme_depense" => "DESC", "LIMIT" => "0,5");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }

    public function saveCommande($data)
    {
        $reponse = $this->save($data);
        return ($reponse) ? array("success" => "Le statut de la commande a été modifié avec success", "reponse" => $reponse) : array("danger", "Le statut de la commande n' a pas été modifié!", "reponse" => $reponse);
    }

    public function activeProduit($id, $statut)
    {
        $commandeDetails = new details_commandeRepository();
        $reponse = $commandeDetails->findDetailById($id);
        $etat = ($statut !== "Annulée") ? "1" : "0";
        if (count($reponse) > 0)
        {
            $produit = new produitRepository();
            foreach ($reponse as $values)
            {
                $produits = $produit->findProduitBy(array("p.id = '$values->produit_id'"));
                $date_arrivee = new \DateTime($produits[0]->date_arrivee);
                $date_arrivee = date_format($date_arrivee, "Y-m-d H:i");
                $date_depart = new \DateTime($produits[0]->date_depart);
                $date_depart = date_format($date_depart, "Y-m-d H:i");
                $result = $produit->saveProduit(array("id" => $values->produit_id, "etat" => "$etat", "date_arrivee" => $date_arrivee, "date_depart" => $date_depart, "salle_id" => $values->salle_id));
            }
        }
    }

    public function chiffreAffaire()
    {

        $filtre = array("date LIKE '%" . date("Y") . "%'", "statut_commande = 'Payée'");
        $result = $this->findCommandeBy($filtre);
        $ca = 0;
        if (count($result) > 0)
        {
            foreach ($result as $values)
            {
                $ca += (float) $values->montant;
            }
        }

        return $ca;
    }

    public function getDetailsCommande($id, $tri = array())
    {
        $detail = new details_commandeRepository();
        return $detail->findDetailById($id, $tri);
    }

}

?>