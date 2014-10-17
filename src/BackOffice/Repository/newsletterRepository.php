<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;
use BackOffice\Repository\membreRepository;

class newsletterRepository extends EntityRepository
{

    public function findNewsletterBy($field = array(), $fil = array(), $tri = array())
    {
        $fields = array("n.id", "n.titre" , "DATE_FORMAT(n.date_envoi,'%d-%m-%Y %H:%i') as date_envoi", "DATE_FORMAT(n.date_creation,'%d-%m-%Y %H:%i') as date_creation", "m.pseudo", "m.prenom", "m.nom","n.contenu");
        $tables = array("newsletter n", );
        $leftJoin = array("membre m"=>"n.membre_id = m.id");
        $filtre = array();
        // Si on ajoute des filtres supplémentaires
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

    public function creerNewsletter($data)
    {
        return $this->save($data);
    }

    public function supprimerNewsletter($id)
    {
        return $this->delete($id);
    }

    public function modifierNewsletter($arg)
    {
       return $this->NewsletterExist($arg['id']) ? $this->save($arg) : false;    
    }
    
    public function NewsletterExist($id)
    {
           $filtre = array("n.id = $id"); 
           return ($this->findNewsletterBy(array(),$filtre)) ? true : false;
    }
    
    
    
    public function findMembreInscrit()
    {
        $membre = new membreRepository();
        return $membre->findMembreBy(array("m.statut_newsletter = '1'"));
    }
    
}

?>