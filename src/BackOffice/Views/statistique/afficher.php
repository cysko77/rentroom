<div class="col-xs-12 padLeft0">
    <h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Afficher les statistiques</h1><br/>
    <br class="clear"/>
    <br/>


    <!--// TOP 5 des salles les mieux notées/////////////////////////////////////////////////////////////////////////////////////////////////-->
    <div class="bs-example col-xs-6 padLeft0 hFixe">
        <h3 class="JauneBcg"><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;Top 5 des salles les mieux notées</h3><br/>
        <?php if(!empty($salles1)) { ?>
        <table id="affiche" class="table-hover table-membre" style="text-align:center !important;width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Note</th>
                    <th>Titre</th>
                    <th>Adresse</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                    <th>Capacité</th>
                    <th>Catégorie</th>
                </tr>
            </thead>
            <tbody class="contenuProduit">
                <?php echo $salles1 ?>
            </tbody>
        </table>
        <?php }else{ echo "<p>Pas de statistiques!!</p>"; }?>
    </div>
    
    
    <!-- TOP 5 des salles les plus vendues/////////////////////////////////////////////////////////////////////////////////////////////////-->
    <div class="bs-example col-xs-6 hFixe" style="padding-right:0">
        <h3 class="JauneBcg"><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;Top 5 des salles les plus vendues</h3><br/>
        <?php if(!empty($salles2)) { ?>
        <table id="affiche" class="table-hover table-membre" style="text-align:center !important;width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nbre de réservation</th>
                    <th>Titre</th>
                    <th>Adresse</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                    <th>Capacité</th>
                    <th>Catégorie</th>
                </tr>
            </thead>
            <tbody class="contenuProduit">
                <?php echo $salles2 ?>
            </tbody>
        </table>
        <?php }else{ echo "<p>Pas de statistiques!!</p>"; }?>
    </div>
    
    
    <!--// TOP 5 des membres qui achété le pus (en termes de quantité)/////////////////////////////////////////////////////////////////////////////////////////////////-->
    <div class="bs-example col-xs-6 padLeft0 hFixe">
        <h3 class="RougeBcg"><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;Top 5 des membres qui achété le plus</h3><br/>
        <?php if(!empty($membres1)) { ?>
        <table id="affiche" class="table-hover table-membre" style="text-align:center !important;width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nbre d'achat</th>
                    <th>Pseudo</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                </tr>
            </thead>
            <tbody class="contenuProduit">
                <?php echo $membres1 ?>
            </tbody>
        </table>
        <?php }else{ echo "<p>Pas de statistiques!!</p>"; }?>
    </div>
    
    
    <!--// TOP 5 des membres qui achété le pus cher (en termes de prix)/////////////////////////////////////////////////////////////////////////////////////////////////-->
    <div class="bs-example col-xs-6 hFixe" style="padding-right:0">
        <h3 class="RougeBcg"><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;Top 5 des membres qui achété le plus cher</h3><br/>
        <?php if(!empty($membres2)) { ?>
        <table id="affiche" class="table-hover table-membre" style="text-align:center !important;width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Montant dépensé</th>
                    <th>Pseudo</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Code postal</th>
                    <th>Ville</th>
                </tr>
            </thead>
            <tbody class="contenuProduit">
                <?php echo $membres2 ?>
            </tbody>
        </table>
        <?php }else{ echo "<p>Pas de statistiques!!</p>"; }?>
    </div>
</div>