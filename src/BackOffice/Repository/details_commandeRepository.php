<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;

class details_commandeRepository extends EntityRepository
{

    public function findDetails($arg)
    {
        return $this->find($arg);
    }

    public function findDetailById($id, $tri = array())
    {
        $fields = array("dc.id, dc.commande_id, dc.prix, DATE_FORMAT(c.date,'%d-%m-%Y %H:%i') as date, c.membre_id, m.pseudo, dc.produit_id, s.titre, dc.salle_id, v.nom_reel ");
        $tables = array("details_commande dc");
        $leftJoin = array("commande c" => "dc.commande_id = c.id", "membre m" => "c.membre_id = m.id", "produit p" => "dc.produit_id = p.id", "salle s" => "dc.salle_id = s.id", "ville_france v" => "s.ville_france_id = v.id");
        $filtre = array("dc.commande_id = $id");
        $args = array("fields" => $fields, "tables" => $tables, "leftJoin" => $leftJoin, "filtre" => $filtre, "tri" => $tri);
        return $this->find($args);
    }

    public function saveDetailsCommande($data)
    {
        return $this->save($data);
    }
    
    
    

}

?>
