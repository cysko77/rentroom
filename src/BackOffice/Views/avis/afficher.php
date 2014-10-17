<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Afficher les avis</h1>
<br/>
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
    if(! empty($avis)){
?>
<div class="bs-example">
    <table id="affiche" class="table table-hover" style="text-align:center !important">
        <thead>
            <tr>
                <th>#
                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Note
                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=note&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=note&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>

                </th>
                <th>Date
                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=date&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=date&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Commentaire
                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=commentaire&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=commentaire&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Salle
                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=salle_id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=salle_id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Membre
                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=membre_id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=avis&action=afficher&triby=membre_id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Supprimer
                </th>
            </tr>
        </thead>
        <tbody id="contenuProduit">
            <?php echo $avis ?>
        </tbody>
    </table>

</div>
<?php echo $pagination ?><br class="clear"/>
<?php }else{ echo "<p>Pas d'avis!!</p>"; }?>
</div>