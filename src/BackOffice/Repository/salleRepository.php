<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;

class salleRepository extends EntityRepository
{

    /**
     * Permet de récupérer toutes les salles
     * @return	array
     */
    public function findAllSalle()
    {
        return $this->findAll();
    }

    /**
     * Cette méthode permet de retourner les salles en fonction de parameters passés dans la methode 
     * @parameters  (array)$fiel => fields (array)$fil => filtre  && (array)$tri => tri  
     * @return	array
     */
    public function findSalleBy($fiel = array(), $fil = array(), $tri = array())
    {
        $fields = array("s.id", "s.titre", "s.description", "s.photo", "s.adresse", "s.cp", "v.nom_reel", "s.capacite", "s.categorie", "s.long_deg", "s.lat_deg", "v.pays", "s.ville_france_id");
        $tables = array("salle s", "ville_france v");
        $filtre = array("v.id = s.ville_france_id");
        if (!empty($fil))
        {
            foreach ($fil as $key => $values)
            {
                array_push($filtre, "$values");
            }
        }
        if (!empty($fiel))
        {
            foreach ($fiel as $key => $values)
            {
                array_push($fields, "$values");
            }
        }
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }
    
    /**
     * Permet de retourner le top 5 des salles les mieux notées
     * @return	array
     */
    public function Top5SallesNotes()
    {
        $fields = array("a.salle_id", "ROUND(AVG(a.note)) as moyenne_avis","s.titre", "s.adresse", "s.cp", "v.nom_reel", "s.capacite", "s.categorie");
        $tables = array("avis a","ville_france v", "salle s");
        $filtre = array("v.id = s.ville_france_id","a.salle_id = s.id");
        $tri = array("GROUP BY"=> "a.salle_id","ORDER BY moyenne_avis" => "DESC", "LIMIT" => "0,5");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }
    
    /**
     * Permet de retourner le top 5 des salles les mieux vendues
     * @return	array
     */
    public function Top5SallesVendues()
    {
        $fields = array("s.id", "COUNT(p.etat) as nbre_de_reservation","s.titre", "s.adresse", "s.cp", "v.nom_reel", "s.capacite", "s.categorie");
        $tables = array("salle s", "ville_france v", "produit p");
        $filtre = array("v.id = s.ville_france_id","p.salle_id = s.id","p.etat = '1'");
        $tri = array("GROUP BY"=> "s.id", "ORDER BY nbre_de_reservation" => "DESC", "LIMIT" => "0,5");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }

    /**
     * Permet de sauver ou updater une salle
     * @ parameters $data => données de la salles à sauver
     * @return	array
     */
    public function saveSalle($data)
    {
        if ($this->salleIsValide($data))
        {
            $reponse = $this->save($data);
            if (isset($data['id']))
            {
                $save = "modifiée";
            }
            else
            {
                $save = "enregistrée";
            }

            if ($reponse)
            {
                // On supprime les données.
                $alert = array("success", "La salle a été $save!");
                $data = array();
            }
            else
            {
                $alert = array("warning", "La salle n'a pas été $save, car les données de la salle n'ont pas changées!");
            }
        }
        else
        {
            $alert = array("danger", "La salle ne peut pas être réservée du $date_arrivee au $date_depart car les dates se chevauchent avec d'autres produits! Veuillez changer les dates!");
            $data['date_arrivee'] = false;
            $data['date_depart'] = false;
        }
        return array("reponse" => $data, "alert" => $alert);
    }

    /**
     * Permet de savoir si la salle n'existe pas déjà
     * @parameters (array) $datSave
     * @return	(boolean)
     */
    public function salleIsValide($dataSave)
    {
        // Une salle est valide si : titre, adresse, cp et ville différents
        $filtre = array("s.titre = '$dataSave[titre]'", "s.adresse = '$dataSave[adresse]'", "s.cp = '$dataSave[cp]'", "s.ville_france_id = '$dataSave[ville_france_id]'");
        if (isset($dataSave['id']))
        {
            array_push($filtre, "s.id != '$dataSave[id]'");
        }
        $result = $this->findSalleBy(array(), $filtre);
        if (count($result) > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Permet de savoir si la salle peut être supprimée (Si la salle n'est pas rattachée à un produit);
     * @return	array
     */
    public function salleIsSupprimable($id)
    {
        $produit = new produitRepository();
        $result = $produit->produitDontHaveSalle($id);
        if (count($result) >= 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Permet de supprimer une salle
     * @parameters (integer) $id => id de la salle
     * @return	array
     */
    public function supprimerSalle($id)
    {
        if ($this->salleIsSupprimable($id))
        {

            if ($this->delete($id))
            {
                return array("success", "La salle a été supprimée!");
            }
            else
            {
                return array("danger", "La salle n'a pas été supprimée car elle n'existe pas!");
            }
        }
        else
        {
            return array("danger", "La salle ne peut pas être supprimée car elle est rattachée à un produit!");
        }
    }

    /**
     * Permet de récupérer la ville de la salle
     * @parameters (array) $arg
     * @return	array
     */
    public function getVille($arg = array())
    {
        $ville = new ville_franceRepository();
        return $ville->findVilleBy($arg);
    }

}

?>
 