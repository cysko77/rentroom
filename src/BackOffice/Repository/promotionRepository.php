<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;
use BackOffice\Repository\produitRepository;

class promotionRepository extends EntityRepository
{

    public function findAllPromotion()
    {
        return $this->findAll();
    }

    public function findPromotionBy($filtre = array(), $tri = array())
    {
        $fields = array("id", "code_promo", "reduction");
        $tables = array("promotion");
        $filtre = $filtre;

        $arg = array("fields" => $fields, "tables" => $tables, "filtre" => $filtre, "tri" => $tri);

        return $this->find($arg);
    }

    public function supprimerPromotion($id)
    {
        return $this->delete($id);
    }

    public function savePromotion($dataSave)
    {

        $modif = (isset($dataSave['id'])) ? true : false;
        if ($this->promotionIsUnique($dataSave['code_promo'], $modif) === "notUnique")
        {
            return (!$modif) ? "notUnique" : false;
        }
        else
        {
            return ($this->save($dataSave)) ? true : false;
        }
    }

    public function getProduit($arg)
    {
        $produit = new produitRepository;
        return $produit->findProduitBy($arg);
    }

    public function promotionIsUnique($code, $modif)
    {

        $filtre = array("code_promo = '$code'");
        if (!$modif)
        {
            return ($this->findPromotionBy($filtre)) ? "notUnique" : true;
        }
        else
        {

            return (count($this->findPromotionBy($filtre)) > 1) ? "notUnique" : true;
        }
    }

}

?>