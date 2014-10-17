<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Afficher les produits</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=produit&action=afficher" class="btn btn-warning">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=produit&action=ajouter"  class="btn btn-default">Ajouter</a>
        </div>
    </div>
</div>
<br class="clear"/>
<br/>
<?php
if (!empty($alert))
{
    ?>

    <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $alert[1]; ?>
    </div>
    <?php
}


    if(! empty($produits)){
?>

<div class="bs-example">
    <table id="affiche" class="table table-hover" style="text-align:center !important">
        <thead>
            <tr>
                <th>#
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Titre
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=titre&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=titre&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>

                </th>
                <th>Date d'arrivée
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=date_arrivee&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=date_arrivee&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Date de départ
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=date_depart&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=date_depart&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Prix
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=prix&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=prix&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Capacité
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=capacite&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=capacite&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Catégorie
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=categorie&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=categorie&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Code postal
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=cp&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=cp&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Ville
                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=nom_reel&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=produit&action=afficher&triby=nom_reel&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody id="contenuProduit">
            <?php echo $produits ?>
        </tbody>
    </table>

</div>
<?php echo $pagination; ?><br class="clear"/>
    <?php }else{ echo "<p>Pas de produits!!</p>"; }?>
</div>