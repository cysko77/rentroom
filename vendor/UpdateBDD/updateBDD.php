<?php

namespace UpdateBDD;

use Manager\PDOManager;

class updateBDD
{

    public function __construct()
    {
        $month = date('m');
        $fichier = __DIR__ . "/month.txt";
        if (!file_exists($fichier))
        {
            $file = fopen($fichier, "a+");
            fwrite($file, $month);
            $file = fclose($file);
        }

        if ($month != file_get_contents($fichier))
        {
            file_put_contents($fichier, ""); // on supprime le contenu du fichier.
            $file = fopen($fichier, "a+");
            fwrite($file, $month);
            $file = fclose($file);
            // On récupère les dates de départ et d'arrivée de tous les produits
            $pdo = PDOManager::getInstance()->getPdo();
            $sql = "SELECT date_arrivee,DATE_ADD(date_arrivee,interval 1 month)as date_arrivee_plus, date_depart ,DATE_ADD(date_depart,interval 1 month)as date_depart_plus FROM produit;";
            $query = $pdo->query($sql);
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            $sql = "UPDATE produit SET date_arrivee = ?, date_depart = ? WHERE date_arrivee = ? AND date_depart = ?";
            $requete = $pdo->prepare($sql);

            foreach ($result as $date)
            {
                // On ajoute un mois à toutes les dates des produits
                $requete->execute(array(
                    $date['date_arrivee_plus'],
                    $date['date_depart_plus'],
                    $date['date_arrivee'],
                    $date['date_depart']
                ));
            }
        }
    }

}

?>