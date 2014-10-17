
<!--
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
Search + Qui sommes-nous ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->
<div class="col-xs-5" style="padding-left:0;margin-top:300px">
    <div style="padding-left:0;position:relative">
        <div  class="col-xs-12 shBx" style="padding:0 10px 10px 10px;position:absolute;top:-480px;background:#ffffff">
            <img style="position:absolute; right:-19px; top:55px" src="lokisalle/images/coinSearch.png" alt="coinSearch">
            <img style="position:absolute; left:-19px; top:55px" src="lokisalle/images/coinSearchLeft.png" alt="coinSearch">
            <div style="background: none repeat scroll 0 0 #B80000;height: 57px; left: -29px; position: relative; width: 506px;">
                <h1 class="rechercheTitle">-- Votre recherche --</h1>
            </div>
            <p style="margin-top:15px">
                Pour trouver la salle de vos rêves , veuillez remplir le formulaire suivant. 
                Plus  vous renseignez de champs plus votre recherche sera précise.
            </p>
            <!--Formulaire de recherche ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
            <form id="form_inscrip"  method="POST" action="index.php?controller=produit&action=recherche">
                <div class="col-xs-6"  style="padding-left:0">

                    
                    <div id="categorie"class="form-group">
                        <label for="categorie" class="control-label">Type d'événement</label>
                        <select name="categorie" class="form-control" id="Selectsexe">
                            <option value="0">Type d'événement</option>
                            <option value="Mariage">Mariage</option>
                            <option value="Céminaire">Céminaire</option>
                            <option value="Réunion">Réunion</option>
                        </select>
                    </div>  

                    
                    <div id="cpVilleAccueil" class="form-group has-feedback">
                        <label for="cp" class="control-label">Code postal</label>
                        <input type="text" name="cp"class="form-control" id="inputCpVilleAccueil" value="" autocomplete="off" placeholder="Code postal">
                        <span id="spanCpVilleAccueil" class="glyphicon form-control-feedback"></span>
                    </div>

                    
                    <div class="form-group has-feedback">
                        <label for="date_arrivee" class="control-label">Date d'arrivée</label>
                        <input type="text" name="date_arrivee" class="form-control" id="date_timepicker_arrivee"  placeholder="Date d'arrivée" value="">
                        <span class="glyphicon form-control-feedback"></span>
                    </div>     
                </div>



                <div class="col-xs-6"  style="padding-right:0"> 

                   
                    <div id="capacite" class="form-group has-feedback">
                        <label for="capacite" class="control-label">Nombre de personne</label>
                        <input type="text" name="capacite" class="form-control" id="inputPrenom" value="" placeholder="Capacité">
                        <span class="glyphicon form-control-feedback"></span>
                    </div>

                    		
                    <div id="ville_france_id_VilleAccueil" class="form-group has-feedback">
                        <label for="ville_france_id" class="control-label">Ville</label><img id="loader_cp_VilleAccueil" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
                        <input name="ville_france_id" class="form-control" id="VilleAccueil" placeholder="La ville" value="">
                        <span class="glyphicon form-control-feedback"></span>
                    </div>

                    
                    <div class="form-group has-feedback">
                        <label for="date_depart" class="control-label">Date départ</label>
                        <input type="text" name="date_depart" class="form-control" id="date_timepicker_depart" placeholder="Date de départ" value="">
                        <span class="glyphicon form-control-feedback"></span>

                    </div>


                    <div class="form-group pull-right" style="margin-top:25px">
                        <button type="submit" class="btn btn-warning">Rechercher</button>
                    </div>
                    <br class="clearfix"/>
                </div>

            </form>
        </div>
        <h1 style="color:#6b6967">Qui sommes-nous ?</h1>
        <p>
            <strong>Provocavit ut flammam primae et ad Phalangio primae principem spectante eius ex ad exulque adulescens noxiarum ex Lollianus ut hos.</strong></p>
        <p>
            Haec et huius modi quaedam innumerabilia ultrix facinorum impiorum bonorumque praemiatrix aliquotiens operatur Adrastia atque utinam
            semper quam vocabulo duplici etiam Nemesim appellamus: ius quoddam sublime numinis efficacis, humanarum mentium opi.<br/>
            Provocavit ut flammam primae et ad Phalangio primae principem spectante eius ex ad exulque adulescens.
        </p>
        <p>
            Haec et huius modi quaedam innumerabilia ultrix facinorum impiorum bonorumque praemiatrix aliquotiens operatur Adrastia atque utinam
            semper quam vocabulo duplici etiam Nemesim appellamus: ius quoddam sublime numinis efficacis, humanarum mentium opi.
        </p>

    </div>
</div>


<!--
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
Trois dernières offres  ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
-->

<div class="col-xs-7" style="padding-left:40px">
    <h1 style="color:#6b6967">Nos 3 dernières offres</h1>
    <?php
    if (!empty($produits))
    {
        ?>
        

        <?php
        foreach ($produits as $values)
        {
            ?>
            <div class="contOffre">

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
                    <div class="col-xs-3" style="padding-left:10px">
                            <?php if ($values->moyenne_avis > 0){ ?> <img style="position:absolute;right : -20px" src="lokisalle/images/star/star<?php echo $values->moyenne_avis; ?>.png" alt="star<?php echo $values->moyenne_avis; ?>"><?php } ?>
                            <span class="avis">(<?php echo $values->nombre_avis; ?> avis)</span>
                        </div>
                        <div class="col-xs-12" style="padding-left:10px">
                            <?php
                            if (!$connected)
                            {
                                ?>
                            
                                <a class="connectLink" style="color:#B80000" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour l'ajouter à votre panier</a>
                            <?php } ?>
                            <br/><a class="btn btn-warning btn-sm" href="index.php?controller=produit&action=detailsProduit&id=<?php echo $values->id; ?>">Voir la fiche détaillée</a>
                            <?php
                            if ($connected)
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
        else {
            echo "Pas de produits!";
        }
        ?>   

    </div>



    <!--
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    Nos attouts, partenaires & confiance :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    -->
    <div class="col-xs-12 pad0" style="padding-left: 0px; padding-bottom: 20px;">
        <div class="col-xs-4" style="padding-left:0"><h1 class="ac1H1">Nos atouts <img src="lokisalle/images/left.png" alt="left"/></h1></div>
        <div class="col-xs-4" style="padding-left:0"><h1 class="ac2H1">Ils nous font confiance <img src="lokisalle/images/right.png" alt="right"/></h1></div>
        <div class="col-xs-4" style="padding-left:0"><h1 class="ac3H1">Nos partenaires</h1></div>
    </div>
    <div class="col-xs-4" style="padding-left:0">
        <p class="accP">
            <strong>+ 30 ans d’expérence</strong> <br/>
            <strong>+ de 60 organisateurs</strong><br/>
            d’événements nationaux <br/>
            Des milliers de lieux et fournisseurs <br/>
            Présence dans <strong>+ de 90 villes en france</strong> <br/>
            Une équipe de <strong>+ de 950 professionnels</strong> <br/><br/>
        </p>
    </div>
    <div class="col-xs-4" style="padding-left:0">
        <p class="accP">
            <img src="lokisalle/images/marques1.jpg" alt=""/>
        </p>
    </div>
    <div class="col-xs-4" style="padding-left:0">
        <p>
            <img src="lokisalle/images/marques2.jpg" alt=""/>
        </p>

    </div>
    <br class="clear" />


    <!--
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    Selection du mois ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
    -->
    <?php
    if (!empty($Suggestionproduits))
    {
        ?>
        <div class="col-xs-12" style="padding-left:0">
            <h1 style="color:#6b6967">Notre séléction du mois</h1>
        </div>
        <?php
        foreach ($Suggestionproduits as $values)
        {
            ?>
            <div class="col-xs-3 mois" style="padding-left:0">
                <div style="border: 1px solid #cecece;padding:10px">
                    <div class="imgMois">
                        <img width="255" src="lokisalle/images/salles/miniature/<?php echo $values->photo; ?> " alt="salle">
                    </div>
                    <div style="padding-left:0px;position:relative">

                        <h3 class="pull-right"><span class="label label-default"  style="position:absolute;right:0px; top:0px"><?php echo $values->prix; ?>€</span></h3>
                        <h4 class="titre"><?php echo strtoupper($data->valeur($values->titre)); ?></h4>
                        <?php if ($values->moyenne_avis > 0){ ?> <img src="lokisalle/images/star/star<?php echo $values->moyenne_avis; ?>.png" alt="star<?php echo $values->moyenne_avis; ?>"><?php } ?>
                        <span style="font-size:11px">(<?php echo $values->nombre_avis; ?> avis)</span>
                        <div class="col-xs-12" style="background:#f3f3f3;padding:10px; margin: 10px 0">
                            <p class="marg0"><span class="glyphicon glyphicon-calendar"></span>&nbsp;Du <?php echo $values->date_arrivee; ?> au <?php echo $values->date_depart; ?></p>
                            <p class="marg0"><span class="glyphicon glyphicon-map-marker"></span>&nbsp;<?php echo $values->nom_reel; ?></p>
                            <p class="marg0"><span class="glyphicon glyphicon-bookmark"></span>&nbsp;<?php echo $values->categorie; ?></p>
                            <p class="marg0"><span class="glyphicon glyphicon glyphicon-user"></span>&nbsp;<?php echo $values->capacite; ?> pers.</p>
                        </div>
                        <p class="marg0" style="height:300px"><?php echo $data->valeur($values->description); ?></p><br/>

                        <?php
                        if (!$connected)
                        {
                            ?>
                            <a class="connectLink" style="color:#B80000; font-size:11px" href="index.php?controller=membre&action=connexion" data-target="#myModalConnexion" data-toggle="modal" onclick="return false;"><span class="glyphicon glyphicon-chevron-right"></span>&nbsp;Connectez-vous pour l'ajouter à votre panier</a><br/><br/>
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
        <?php } ?>
    <?php } ?>