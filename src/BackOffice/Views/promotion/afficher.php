<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Afficher les promotions</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=promotion&action=afficher" class="btn btn-warning">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=promotion&action=ajouter"  class="btn btn-default">Ajouter</a>
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
    if(! empty($promotion)){
?>


<div class="bs-example">
    <table id="affiche" class="table table-hover" style="text-align:center !important">
        <thead>
            <tr>
                <th>#
                    <a onclick="return false;" class="link-orange" href="index.php?controller=promotion&action=afficher&triby=id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=promotion&action=afficher&triby=id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Code de promotion
                    <a onclick="return false;" class="link-orange" href="index.php?controller=promotion&action=afficher&triby=code_promo&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=promotion&action=afficher&triby=code_promo&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>

                </th>
                <th>Réduction (%)
                    <a onclick="return false;" class="link-orange" href="index.php?controller=promotion&action=afficher&triby=reduction&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=promotion&action=afficher&triby=reduction&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody id="contenuProduit">
            <?php echo $promotion ?>
        </tbody>
    </table>

</div>
<?php echo $pagination ?><br class="clear"/>
<?php }else{ echo "<p>Pas de promotions!!</p>"; }?>
</div>