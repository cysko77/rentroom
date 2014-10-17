<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Toutes les newsletters</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=newsletter&action=afficher" class="btn btn-warning">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=newsletter&action=ajouter"  class="btn btn-default">Créer</a>
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
    if(! empty($newsletters)){
?>

<div class="bs-example">
    <table id="affiche" class="table table-hover" style="text-align:center !important">
        <thead>
            <tr>
                <th>#
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Titre
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=titre&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=titre&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>

                </th>
                <th>Date d'envoi
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=date_envoi&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=date_envoi&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Date de création
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=date_creation&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=date_creation&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                
                <th>Créer par
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=pseudo&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=pseudo&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Prénom
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=prenom&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=prenom&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Nom
                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=nom&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=newsletter&action=afficher&triby=nom&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Envoyer</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody id="contenuProduit">
            <?php echo $newsletters ?>
        </tbody>
    </table>

</div>
<?php echo $pagination ?><br class="clear"/>
<?php }else{ echo "<p>Pas de newsletters!!</p>"; }?>
</div>