<?php

$avis = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "commentaire" => array("text"),
    "note" => array("int" => array("min" => 1, "max" => 5)),
    "date" => array("datetime"),
    "salle_id" => array("int"),
    "membre_id" => array("int")
);


$commande = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "montant" => array("float" => array("decimal" => 2)),
    "date" => array("datetime"),
    "statut_commande" => array("enum" => array("En attente de paiement", "Payée", "Annulée")),
    "membre_id" => array("int")
);


$details_commande = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "commande_id" => array("int"),
    "produit_id" => array("int", null),
    "prix" => array("float" => array("decimal" => 2)),
    "salle_id" => array("int")
);


$membre = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "pseudo" => array("varchar" => array("min" => 3, "max" => 15)),
    "mdp" => array("varchar" => array("min" => 3, "max" => 32)),
    "nom" => array("varchar" => array("min" => 1, "max" => 20)),
    "prenom" => array("varchar" => array("min" => 1, "max" => 30)),
    "email" => array("email" => array("min" => 6, "max" => 45)),
    "sexe" => array("enum" => array("Homme", "Femme")),
    "adresse" => array("varchar" => array("min" => 1, "max" => 30)),
    "statut_membre" => array("enum" => array("0", "1")),
    "role_id" => array("enum" => array("0", "1")),
    "token_valid" => array("int", null),
    "token_mdp" => array("varchar" => array("min" => 0, "max" => 45), null),
    "statut_newsletter" => array("enum" => array("0", "1")),
    "token_secu" => array("varchar" => array("min" => 0, "max" => 45), null),
    "photo" => array("varchar" => array("min" => 0, "max" => 200), null),
    "ville_france_id" => array("int"),
    "cp" => array("int" => array("min" => 01000, "max" => 99999))
);


$newsletter = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "titre" => array("varchar" => array("min" => 1, "max" => 45)),
    "contenu" => array("text"),
    "date_envoi" => array("datetime", null),
    "date_creation" => array("datetime"),
    "membre_id" => array("int")
);


$produit = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "date_arrivee" => array("datetime"),
    "date_depart" => array("datetime"),
    "prix" => array("float" => array("decimal" => 2)),
    "etat" => array("enum" => array("0", "1")),
    "promotion_id" => array("int", null),
    "salle_id" => array("int"),
    "membre_id" => array("int")
);


$promotion = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "code_promo" => array("varchar" => array("min" => 1, "max" => 45)),
    "reduction" => array("int" => array("min" => 1, "max" => 100))
);


$salle = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "adresse" => array("text"),
    "titre" => array("varchar" => array("min" => 1, "max" => 200)),
    "description" => array("text"),
    "photo" => array("varchar" => array("min" => 0, "max" => 200), null),
    "capacite" => array("int" => array("min" => 1, "max" => 10000)),
    "categorie" => array("enum" => array("Reunion", "Mariage", "Ceminaire")),
    "ville_france_id" => array("int"),
    "long_deg" => array("float" => array("decimal" => 25), null),
    "lat_deg" => array("float" => array("decimal" => 25), null),
    "cp" => array("int")
);


$ville_france = array(
    "id" => array("int"=> array("min" => 1, "max" => 99999999999999999)),
    "pays" => array("varchar" => array("min" => 1, "max" => 45)),
    "departement" => array("varchar" => array("min" => 1, "max" => 3)),
    "slug" => array("varchar" => array("min" => 1, "max" => 225)),
    "nom" => array("varchar" => array("min" => 1, "max" => 45)),
    "nom_reel" => array("varchar" => array("min" => 1, "max" => 45)),
    "code_postal" => array("varchar" => array("min" => 1, "max" => 225)),
    "code_commune" => array("varchar" => array("min" => 1, "max" => 5)),
    "arrondissement" => array("int", null),
    "longitude_deg" => array("float" => array("decimal" => 25)),
    "latitude_deg" => array("float" => array("decimal" => 25)),
    "cp" => array("int")
);
