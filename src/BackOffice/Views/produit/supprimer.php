<?php

if ($produits)
{
    echo 'Liste des produits:';
    foreach ($produits as $produit)
    {
        echo " <hr/>$produit->id - $produit->prix";
    }
}
else
{
    echo "Il n' y a pas résultat!!";
}