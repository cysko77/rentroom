<?php

namespace BackOffice\Repository;

use Manager\EntityRepository;
use BackOffice\Repository\salleRepository;
use BackOffice\Repository\commandeRepository;

class statistiqueRepository extends EntityRepository
{

     /**
     * Permet de retourner le top 5 des salles les mieux notées
     * @return	array
     */
    public function Top5SN()
    {
        $salle = new salleRepository;
        return  $salle->Top5SallesNotes();    
    }


    /**
     * Permet de retourner le top 5 des salles les mieux vendues
     * @return	array
     */
    public function Top5SV()
    {
        $salle = new salleRepository;
        return  $salle->Top5SallesVendues();    
    }
    
    
     /**
     * Permet de retourner le top 5 des membres qui achète le plus (quantité)
     * @return	array
     */
    public function Top5MQ()
    {
        $commande = new commandeRepository;
        return  $commande->Top5MembresQuantite();    
    }


    /**
     * Permet de retourner le top 5 des membres qui achète le plus (Prix)
     * @return	array
     */
    public function Top5MP()
    {
        $commande = new commandeRepository;
        return  $commande->Top5MembresPrix();    
    }
}

?>
 