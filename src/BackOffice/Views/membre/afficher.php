<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Afficher les membres</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=membre&action=afficher" class="btn btn-warning">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=membre&action=ajouter"  class="btn btn-default">Ajouter</a>
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
?>


<div class="bs-example">
    <table id="affiche" class="table-hover table-membre" style="text-align:center !important;width:100%">
        <thead>
            <tr>
                <th>#
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>&nbsp;&nbsp;
                    </a>
                </th>
                <th>Pseudo
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=pseudo&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=pseudo&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>&nbsp;&nbsp;
                    </a>

                </th>
                <th>Prénom
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=prenom&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=prenom&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>&nbsp;&nbsp;
                    </a>
                </th>
                <th>Nom
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=nom&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=nom&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>&nbsp;&nbsp;
                </th>
                <th>Adresse mail
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=email&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=email&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>

                <th>Adresse
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=adresse&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=adresse&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>&nbsp;&nbsp;
                    </a>
                </th>
                <th>Code postal&nbsp;
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=cp&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=cp&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Ville&nbsp;
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=nom_reel&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=nom_reel&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>
                    </a>
                </th>
                <th>Statut
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=statut_membre&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=statut_membre&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>&nbsp;&nbsp;
                    </a>
                </th>
                <th>Rôle
                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=role_id&tri=asc">
                        <span class="glyphicon glyphicon-chevron-asc"></span>
                    </a>

                    <a onclick="return false;" class="link-orange" href="index.php?controller=membre&action=afficher&triby=role_id&tri=desc">
                        <span class="glyphicon glyphicon-chevron-desc"></span>&nbsp;&nbsp;
                    </a>
                </th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody id="contenuProduit">
            <?php echo $membres ?>
        </tbody>
    </table>

</div>
<?php echo $pagination ?><br class="clear"/>
</div>