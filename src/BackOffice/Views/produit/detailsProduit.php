<?php
if(!empty ($produits['produit'])){
?>
<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
GoogleMap:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->
<div id="map-canvas"style="height:450px; width:1145px; margin-top:30px"></div>

<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
Colonne LEFT:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->

<div  class="col-xs-5" style="padding-left:0;margin-left:15px">
    <div id="relLeft" style="padding-left:0;position:relative;float:left;">
        <div  id="absLeft" class="col-xs-12 shBx" style="padding:10px;position:relative;top:-100px;background:#ffffff;z-index: 100;">
            <div class="col-xs-5" style="padding-left:0px; height:258px;width:450px; overflow:hidden;margin-bottom:30px">
                <img width="450" src="lokisalle/images/salles/miniature/grande/<?php echo $produits['produit'][0]->photo; ?>" alt="salle">
            </div>
            <div style="padding-left:0px;position:relative">
                <img style="position:absolute; right:-18px; top:312px" src="lokisalle/images/coinPrix.png" alt="coinPrix">
                <h3 class="pull-right"><span  style="position:absolute;right:-18px; top:287px;background:#cecece; width: 160px;text-align:center"><?php echo $produits['produit'][0]->prix; ?>*€</span></h3>
                <span style="position:absolute; right:0px;top:320px;font-size:11px">* Tous les prix sont hors taxe</span>
                <h3 class="titre"  style="width:300px"><?php echo strtoupper($data->valeur($produits['produit'][0]->titre)); ?></h3>
                <?php if ($produits['produit'][0]->moyenne_avis !== null)
                {
                    ?>
                    <img src="lokisalle/images/star/star<?php echo $produits['produit'][0]->moyenne_avis; ?>.png" alt="star<?php echo $produits['produit'][0]->moyenne_avis; ?>">
<?php } ?>
                <span style="font-size:11px"> (<?php echo $produits['produit'][0]->nombre_avis; ?> avis)</span>
                <div class="col-xs-12" style="background:#f3f3f3;padding:10px; margin: 10px 0">
                    <p class="marg0">
                        <span class="glyphicon glyphicon-calendar"></span>
                        &nbsp;Du <?php echo $produits['produit'][0]->date_arrivee; ?> au <?php echo $produits['produit'][0]->date_depart; ?>
                    </p>
                    <p class="marg0"><span class="glyphicon glyphicon-map-marker"></span>
                        &nbsp;<?php echo $data->valeur($produits['produit'][0]->adresse) . "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $produits ['produit'][0]->cp . "&nbsp;- " . $data->valeur($produits ['produit'][0]->nom_reel); ?>
                    </p>
                    <p class="marg0"><span class="glyphicon glyphicon-bookmark"></span>
                        &nbsp;<?php echo $produits['produit'][0]->categorie; ?>
                    </p>
                    <p class="marg0"><span class="glyphicon glyphicon glyphicon-user"></span>
                        &nbsp;<?php echo $produits['produit'][0]->capacite; ?> pers.
                    </p>
                    <p style="position:absolute;top:85px;right:10px">
                        <?php if (!$connected)
                        {
                            ?>
                            <a class="connectLink" style="color:#B80000" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour l'ajouter à votre panier</a><br/><br/>
                            <?php
                        }
                        if ($connected)
                        {
                            ?>
                            <a class="btn btn-danger btn-sm" href="index.php?controller=produit&action=ajouterProduitPanier&id=<?php echo $produits['produit'][0]->id; ?>">Ajouter au panier</a>
<?php } ?>
                    </p>
                </div>
                <p class="marg0"><?php echo nl2br($data->valeur($produits['produit'][0]->description)); ?></p><br/>

                <!--Commentaires ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
                <h3 class="titre">Avis sur la salle</h3>
                <?php
                if (!empty($produits['avis']))
                {
                    foreach ($produits['avis'] as $values)
                    {
                        ?>
                        <p class="marg0" style="position:relative;min-height:65px">
                            <img style="position:absolute;top:0;right:0" src="lokisalle/images/star/star<?php echo $values->note; ?>.png" alt="star<?php echo $values->note; ?>">
                            <span style="padding-left:0" class="col-xs-3">
                                <img width="75" src="lokisalle/images/membres/<?php echo $values->photo; ?>" alt="membre">
                            </span>
                            <span class="col-xs-9" style="padding-left:0;font-size:12px; color:#D2322D"> 
                            <?php echo ucfirst($values->prenom); ?> , le <?php echo $values->date; ?>
                            </span>
                        <?php echo $data->valeur($values->commentaire); ?> 
                        </p>
                        <hr/>
                        <?php
                    }
                }
                ?>
                        <div class="pull-right col-xs-12 pad0"><?php echo $pagination; ?></div> 
   
                <?php
                if($connected && !$dejaCommente){
                if (!empty($alert))
                {
                    ?>
                    
                    <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $alert[1]; ?>
                    </div>
                    <?php
                }
                ?>
                <form id="form_inscrip"  method="POST" action="index.php?controller=produit&action=avis&id=<?php echo $produits['produit'][0]->id; ?>">
                    
                    <div class="col-xs-12"  style="padding:0">

                        <div id="adresse" class="form-group" style="margin-bottom:14px">
                            <label for="commentaire" class="control-label">Commentaire</label><span class="red">*</span><div id="star" class="pull-right"></div>
                            <textarea name="commentaire" class="form-control" rows="8" id="textareaAdresse" style="height:183px" placeholder="Votre commentaire"></textarea>
                            <input type="hidden" value="<?php echo $produits['produit'][0]->salle_id; ?>" name="salle_id">
                        </div>   
                    </div>
                    <div class="form-group pull-right" style="margin-top:25px">
                        <button type="submit" class="btn btn-warning">Envoyer</button>
                    </div>
                    <br class="clearfix"/>
                </form>

                <?php }
                else 
                {
                    if(!$connected)
                    {
                        echo '<a class="connectLink" style="color:#B80000" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour commenter la salle</a>';
                   
                    }
                    if($dejaCommente)
                    {
                        echo '<div class="alert alert-info alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <strong>Vous avez déjà commenté cette salle!</strong> Vous ne pouvez pas ajouter un autre commentaire!
                              </div>';
                   
                    }
                    }
                ?>           
            
            </div>

        </div>
        
    </div>
</div>

<script>
    var long = <?php echo ($produits['produit'][0]->long_deg) ? $produits['produit'][0]->long_deg : 0; ?>;
    var lat = <?php echo ($produits['produit'][0]->lat_deg) ? $produits['produit'][0]->lat_deg : 0; ?>;
</script>


<!--::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
Colonne RIGHT (Autres suggestions)::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->
<div class="col-xs-7" style="padding-left:20px; width: 647px;">
    <?php
    if (!empty($Suggestionproduits))
    {
        ?>
        <h1 style="color:#6b6967">Autres Suggestions</h1>

    <?php foreach ($Suggestionproduits as $values)
    {
        ?>
            <div class=" contOffre">

                <div class="col-xs-5" style="padding-left:0px; height:158px;width:255px; overflow:hidden">
                    <img width="280" src="lokisalle/images/salles/miniature/<?php echo $values->photo; ?>" alt="salle">
                </div>
                <div class="col-xs-7" style="padding-left:10px">
                    <div class="col-xs-9" style="padding-left:10px">
                        <h4 class="titre"><?php echo strtoupper($data->valeur($values->titre)); ?></h4>
                        <p class="marg0"><span class="glyphicon glyphicon-calendar"></span>&nbsp;Du <?php echo $values->date_arrivee; ?> au <?php echo $values->date_depart; ?></p>
                        <p class="marg0"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $data->valeur($values->nom_reel); ?></p>
                        <p class="marg0"><span class="glyphicon glyphicon-bookmark"></span>&nbsp;<?php echo $values->categorie; ?></p>
                        <p class="marg0"><span class="glyphicon glyphicon glyphicon-user"></span>&nbsp;<?php echo $values->capacite; ?> pers.</p>

                    </div>
                    <div class="col-xs-3" style="padding-left:0px">
                        <?php if ($values->moyenne_avis > 0){ ?> <img style="position:absolute;right : -15px" src="lokisalle/images/star/star<?php echo $values->moyenne_avis; ?>.png" alt="star<?php echo $values->moyenne_avis; ?>"><?php } ?>
                        <span class="avis1">(<?php echo $values->nombre_avis; ?> avis)</span>


                    </div>
                    <div class="col-xs-12" style="padding-left:10px">
                        <?php if (!$connected)
                        {
                            ?>
                            <a class="connectLink" style="color:#B80000" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour l'ajouter à votre panier</a>
                        <?php } ?>
                        <br/><a class="btn btn-warning btn-sm" href="index.php?controller=produit&action=detailsProduit&id=<?php echo $values->id; ?>">Voir la fiche détaillée</a>
        <?php if ($connected)
        {
            ?>
                            <a class="btn btn-danger btn-sm" href="index.php?controller=produit&action=ajouterProduitPanier&id=<?php echo $values->id; ?>">Ajouter au panier</a>
        <?php } ?>

                    </div>   
                </div>
                <h3 class="pull-right"><span class="label label-default"  style="position:absolute;right:0px; top: 40px"><?php echo $values->prix; ?>€</span></h3>
            </div><hr/>

            <?php
        }
    }
    else
    {
        echo "<h2>Nous avons pas d'autres suggestions de salles à vous faire!</h2>";
    }
    ?>     

</div>
<br class="clear"/>

<?php } ?>