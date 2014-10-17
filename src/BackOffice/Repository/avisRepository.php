<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;

class avisRepository extends EntityRepository
{

    public function findAllAvis()
    {
        return $this->findAll();
    }

    // Permet d'afficher les avis d'une salle donnée
    public function findAvis($fil = array(), $tri = array())
    {
        $fields = array("a.id", "a.note", "DATE_FORMAT(a.date,'%d-%m-%Y %H:%i') as date", "a.commentaire", "a.salle_id", "a.membre_id", "m.prenom", "m.photo");
        $tables = array("avis a");
        $leftJoin = array("membre m" => "a.membre_id = m.id");
        $filtre = array();
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

    public function ajouterAvis($data)
    {
        return $this->save($data);
    }

    public function supprimerAvis($id)
    {
        return ($this->delete($id)) ? array("success","L'avis a été supprimé avec succès.") : array("danger", "L'avis n'a pas été supprimé.");
    }
    
    public function noteSalle($id)
    {
        $fields = array("ROUND(AVG(a.note)) as moyenne_avis", "COUNT(a.id) as nombre_avis ");
        $tables = array("avis a");
        $filtre = array("a.salle_id = '$id'");
        if (!empty($fil))
        {
            foreach ($fil as $key => $values)
            {
                array_push($filtre, "$values");
            }
        }
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => array());
        return $this->find($args);
    }
}

?>