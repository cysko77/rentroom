<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;
use BackOffice\Repository\commandeRepository;
use BackOffice\Entity\membreEntity;

class membreRepository extends EntityRepository
{

    /**
     * @variable   array
     */
    protected $unique = array();
    

    /*
     * return member 
     * @parameters  (array)$fil && (array)$etri  
     * $fil = filtre les données et $tri = trie les données retournées
     * @return	array
     */
    public function findMembreBy($fil = array(), $tri = array())
    {
        $fields = array("m.id", "m.pseudo", "m.prenom", "m.nom", "m.email", "m.adresse", "m.cp", "v.nom_reel", "m.statut_membre", "m.role_id", "m.photo", "m.token_secu", "m.sexe", "m.statut_newsletter", "m.ville_france_id");
        $tables = array(" membre m", "ville_france v");
        $filtre = array("m.ville_france_id = v.id");
        // Si on ajoute des filtres supplémentaires
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


    /*
     * Cette méthode permet d'ajouter un membre
     * @parameters  (array)$data  
     * $data => données relatives aux infos du "membre"
     * @return	array
     */
    public function create(membreEntity $membre)
    {

        if ($this->membreNotExit($membre))
        {
            
            $reponse = $this->save($membre);
            if ($reponse)
            {
                $alert = array("success", "Le compte a été enregistré!");
            }
            else
            {
                // Message : Le produit n'a pas été sauvé!
                $alert = array("danger", "Le compte n'a pas été enregistré! Vérifiez le formulaire!");
            }
        }
        else
        {
            $reponse = 0;
            foreach ($this->unique as $key => $values)
            {
                $method = "set".ucfirst($key);
                $membre->$method  = $values;
            }
            if (count($this->unique) < 2)
            {
                $key = array_keys($this->unique);
                $alert = "Ce $key[0] est déjà utilisé par un autre compte";
            }
            else
            {
                $alert = "Ce mail et ce speudo sont déjà utilisés par un autre compte";
            }
            $alert = array("danger", "$alert");
        }
        return array("reponse" => $reponse, "alert" => $alert);
    }

    /*
     * Cette méthode permet de savoir si le membre existe déjà avant de l'ajouter 
     * @parameters  (array)$data
     * $data => données relatives aux infos du "membre"
     * @return	(boolean)
     */
    private function membreNotExit($member)
    {
        $filtre = array("(m.pseudo = '" . $member->getPseudo() . "' OR m.email = '" . $member->getEmail() . "')");
        if (count($reponse = $this->findMembreBy($filtre)) == 1)
        {
            if ($member->getEmail() === $reponse[0]->email)
            {
                $this->unique += array("email" => false);
            }
            if ($member->getPseudo() === $reponse[0]->pseudo)
            {
                $this->unique += array("pseudo" => false);
            }
            return false;
        }
        else
        {
            return true;
        }
    }

    /*
     * Cette méthode permet de supprimer un membre 
     * @parameters  (integer)$id
     * $id => id du membre
     * @return	(array)
     */
    public function supprimerMembre($id)
    {

        if ($this->delete($id))
        {
            return array("success", "Le compte a été supprimé!");
        }
        else
        {
            return array("danger", "Le compte n'a pas été supprimé car il n'existe pas!");
        }
    }

    /*
     * Cette méthode permet de modifier les informations du membre
     * @parameters  (array)$data
     * $data => données relatives aux infos du "membre"
     * @return	(array)
     */
    public function modifierMembre($data, $check = true)
    {
        $dejaMembre = ($check) ? !$this->membreNotExit($data) : true;
        if ($dejaMembre)
        {
            $reponse = $this->save($data);
            if ($reponse)
            {
                $alert = array("success", "Le compte a été modifié!");
                $data = array();
            }
            else
            {
                $alert = array("warning", "Le compte n'a pas été modifié, car les données du compte n'ont été changées!");
            }
        }
        else
        {
            $alert = array("danger", "Le compte n'a pas été modifié! Vérifiez le formulaire!");
        }
        return array("reponse" => $data, "alert" => $alert);
    }

    /*
     * Cette méthode permet de récupérer les commandes d'un produit
     * @parameters  (integer)$id
     * $id => id du "membre"
     * @return	(array)
     */
    public function getCommandeByMembre($id, $tri)
    {
        $commande = new commandeRepository();
        $commandes = $commande->findCommandeBy(array("membre_id = $id"), $tri);
        if (count($commandes) > 0)
        {
            return $commandes;
        }
        else
        {
            return array();
        }
    }

}

?>