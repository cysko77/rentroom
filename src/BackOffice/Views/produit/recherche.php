
<!--
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
SearchForm ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Votre recherhche</h1><br/>
<div style="padding-left:0;position:relative;margin-top: 70px">
    <div style="padding:15px ;background:#FFF0D6;margin-right:15px">
        <p style="margin-top:15px">
            <span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;&nbsp;Pour trouver la salle de vos rêves, veuillez remplir le formulaire suivant. 
            Plus  vous renseignez de champs plus votre recherche sera précise.
        </p><br/>
        
        <?php
        if (!empty($alert))
        {
            ?>
            <br/>
            <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $alert[1]; ?>
            </div>
            <?php
        }
        ?>
        <!--Formulaire de recherche ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
        <form id="form_inscrip"  method="POST" action="index.php?controller=produit&action=recherche">
            <div class="col-xs-6"  style="padding-left:0">

                
                <div id="categorie"class="form-group">
                    <label for="categorie" class="control-label">Type d'événement</label>
                    <select name="categorie" class="form-control" id="Selectsexe">
                        <option value="" <?php
                        if ($data->valeur($dataReponse, "categorie") === "")
                        {
                            echo "selected";
                        }
                        ?> >Type d'événement</option>
                        <option value="Mariage" <?php
                        if ($data->valeur($dataReponse, "categorie") === "Mariage")
                        {
                            echo "selected";
                        }
                        ?> >Mariage</option>
                        <option value="Céminaire" <?php
                        if ($data->valeur($dataReponse, "categorie") === "Céminaire")
                        {
                            echo "selected";
                        }
                        ?> >Céminaire</option>
                        <option value="Réunion" <?php
                        if ($data->valeur($dataReponse, "categorie") === "Réunion")
                        {
                            echo "selected";
                        }
                        ?> >Réunion</option>
                    </select>
                </div>  

                
                <div id="cpVilleAccueil" class="form-group has-feedback">
                    <label for="cp" class="control-label">Code postal</label>
                    <input type="text" name="cp"class="form-control" id="inputCpVilleAccueil" value="<?php echo $data->valeur($dataReponse, "cp"); ?>" autocomplete="off" placeholder="Code postal">
                    <span id="spanCpVilleAccueil" class="glyphicon form-control-feedback"></span>
                </div>

                
                <div class="form-group has-feedback">
                    <label for="date_arrivee" class="control-label">Date d'arrivée</label>
                    <input type="text" name="date_arrivee" class="form-control" id="date_timepicker_arrivee"  placeholder="Date d'arrivée" value="<?php echo $data->valeur($dataReponse, "date_arrivee");?>">
                    <span class="glyphicon form-control-feedback"></span>
                </div>     
            </div>



            <div class="col-xs-6"  style="padding-right:0"> 

                
                <div id="capacite" class="form-group has-feedback">
                    <label for="capacite" class="control-label">Nombre de personne</label>
                    <input type="text" name="capacite" class="form-control" id="inputPrenom" value="<?php echo $data->valeur($dataReponse, "capacite"); ?>" placeholder="Capacité">
                    <span class="glyphicon form-control-feedback"></span>
                </div>

                	
                <div id="ville_france_id_VilleAccueil" class="form-group has-feedback">
                    <label for="ville_france_id" class="control-label">Ville</label><img id="loader_cp_VilleAccueil" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
                    <input name="ville_france_id" class="form-control" id="VilleAccueil" placeholder="La ville" value="<?php echo $data->valeur($dataReponse, "ville_france_id"); ?>">
                    <span class="glyphicon form-control-feedback"></span>
                </div>

                
                <div class="form-group has-feedback">
                    <label for="date_depart" class="control-label">Date départ</label>
                    <input type="text" name="date_depart" class="form-control" id="date_timepicker_depart" placeholder="Date de départ" value="<?php echo $data->valeur($dataReponse, "date_depart");?>">
                    <span class="glyphicon form-control-feedback"></span>

                </div>


                <div class="form-group pull-right" style="margin-top:25px">
                    <button type="submit" class="btn btn-warning">Rechercher</button>
                </div>
                <br class="clearfix"/>
            </div>

        </form>
        <br class="clear" />
    </div>
    
    <br class="clear" /><br/>
    
    
<!--
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
SearchResult ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->    
    <div class="col-xs-12 pad0">
        <?php
    if (!empty($produits) && $produits !== false  && empty($alert))
    {
        ?>
        <div class="alert alert-info alert-dismissable margRight15">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <p>
                <span class="glyphicon glyphicon-search"></span>
                &nbsp;&nbsp;<strong>Nombre de résultat(s) : </strong> <?php echo $nbreProduit; ?> produit(s) trouvé(s)
            </p>
        </div>
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
                            <a class="connectLink" style="color:#B80000; font-size:11px" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour l'ajouter à votre panier</a><br/><br/>
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
    <?php }
    if (empty($produits) && $produits !== false  && empty($alert)){
    ?>
        
        <div class="alert alert-info alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <p>
                <span class="glyphicon glyphicon-search"></span>
                &nbsp;&nbsp;<strong>Nombre de résultat : </strong> 0 produit trouvé
            </p>
        </div> 
    <?php } ?>
    </div>

</div>







 
