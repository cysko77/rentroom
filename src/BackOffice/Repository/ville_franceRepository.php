<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;

class ville_franceRepository extends EntityRepository
{

    public function findAllVille()
    {
        $fields = array("id", "nom_reel");
        return $this->findAll($fields);
    }

    public function findVilleBy($arg = array())
    {
        (!empty($arg)) ? $cp = $arg[0] : $cp = 0;
        $fields = array("v.id", "v.nom_reel");
        $tables = array("ville_france v");
        $filtre = array("v.code_postal LIKE '%$cp%'");
        $args = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre);
        return $this->find($args);
    }

}

?>