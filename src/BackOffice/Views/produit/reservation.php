
<!--
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
SearchForm ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->
<h1 class="pull-left col-xs-12 pad0"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Tous nos produits</h1><br/><br/>
<?php
    if (!empty($produits))
    {
        ?>
        
        <?php
        foreach ($produits as $values)
        {
            ?>
            <div class="col-xs-3 mois block">
                <div style="border: 1px solid #cecece;padding:10px">
                    <div class="imgMois">
                        <img width="255" src="lokisalle/images/salles/miniature/<?php echo $values->photo; ?> " alt="salle">
                    </div>
                    <div class="infoProduit">

                        <h3 class="pull-right"><span class="label label-default"  style="position:absolute;right:0px; top:0px"><?php echo $values->prix; ?>€</span></h3>
                        <h4 class="titre"><?php echo strtoupper($data->valeur($values->titre)); ?></h4>
                        <?php if ($values->moyenne_avis > 0){ ?> <img src="lokisalle/images/star/star<?php echo $values->moyenne_avis; ?>.png" alt="star<?php echo $values->moyenne_avis; ?>"><?php } ?>
                        <span style="font-size:11px">(<?php echo $values->nombre_avis; ?> avis)</span>
                        <div class="col-xs-12" style="background:#f3f3f3;padding:10px; margin: 10px 0">
                            <p class="marg0"><span class="glyphicon glyphicon-calendar"></span>&nbsp;Du <?php echo $values->date_arrivee; ?> au <?php echo $values->date_depart; ?></p>
                            <p class="marg0"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $data->valeur($values->nom_reel); ?></p>
                            <p class="marg0"><span class="glyphicon glyphicon-bookmark"></span>&nbsp;<?php echo $values->categorie; ?></p>
                            <p class="marg0"><span class="glyphicon glyphicon glyphicon-user"></span>&nbsp;<?php echo $values->capacite; ?> pers.</p>
                        </div>
                        <br class="clear"/>

                        <?php
                        if (!$connected)
                        {
                            ?>
                            <a class="connectLink" style="color:#B80000;font-size:11px" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour l'ajouter à votre panier</a><br/><br/>
                            <?php } else {?>
                            <p>&nbsp;</p>
                            <?php } ?>
                            <a class="btn btn-warning btn-sm" href="index.php?controller=produit&action=detailsProduit&id=<?php echo $values->id; ?>">Voir la fiche détaillée</a>
                            <?php
                        if ($connected)
                        {
                            ?>  
                           <a class="btn btn-danger btn-sm" href="index.php?controller=produit&action=ajouterProduitPanier&id=<?php echo $values->id; ?>">Ajouter au panier</a>
                        <?php } ?>
                    </div>
                </div>
                
            </div>
        
        <?php }; ?>
        <div class="pull-right col-xs-12"><?php echo $pagination; ?></div> 
    <?php } else{ echo "<p>Pas de produits!!</p>"; }?>