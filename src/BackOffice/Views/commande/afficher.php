<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Afficher les commandes</h1>
<br/><h5 class="pull-right">Le chiffre d'affaire du 1er Janvier <?php echo date("Y"); ?> à ce jour:&nbsp;&nbsp;<?php echo "<strong><span id=\"ca\" class=\"label label-warning\" style=\"font-size:16px\">" .$ca."€</span></strong></h5>"; ?>

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
    if(! empty($commandes)){
?>
<div id="message"></div>

<div class="bs-example">
    <form class="form" id="form" action="index.php?controller=commande&action=modifier" method ="post">
        <table id="affiche" class="table table-hover" style="text-align:center !important">
            <thead>
                <tr>
                    <th>#
                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=id&tri=asc">
                            <span class="glyphicon glyphicon-chevron-asc"></span>
                        </a>

                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=id&tri=desc">
                            <span class="glyphicon glyphicon-chevron-desc"></span>
                        </a>
                    </th>
                    <th>Date
                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=date&tri=asc">
                            <span class="glyphicon glyphicon-chevron-asc"></span>
                        </a>

                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=date&tri=desc">
                            <span class="glyphicon glyphicon-chevron-desc"></span>
                        </a>

                    </th>
                    <th>Montant (TTC)
                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=montant&tri=asc">
                            <span class="glyphicon glyphicon-chevron-asc"></span>
                        </a>

                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=montant&tri=desc">
                            <span class="glyphicon glyphicon-chevron-desc"></span>
                        </a>
                    </th>
                    <th>Statut
                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=statut_commande&tri=asc">
                            <span class="glyphicon glyphicon-chevron-asc"></span>
                        </a>

                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=statut_commande&tri=desc">
                            <span class="glyphicon glyphicon-chevron-desc"></span>
                        </a>
                    </th>
                    <th>Membre
                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=membre_id&tri=asc">
                            <span class="glyphicon glyphicon-chevron-asc"></span>
                        </a>

                        <a onclick="return false;" class="link-orange" href="index.php?controller=commande&action=afficher&triby=membre_id&tri=desc">
                            <span class="glyphicon glyphicon-chevron-desc"></span>
                        </a>
                    </th>

                </tr>
            </thead>
            <tbody id="contenuProduit">
                <?php echo $commandes ?>
            </tbody>
        </table>
    </form>
</div>
<?php echo $pagination ?><br class="clear"/>
<?php }else{ echo "<p>Pas de commandes!!</p>"; }?>
<!-- details:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::-->
<?php if (!empty($details))
{
    ?>
    <br/><h1 class="pull-left"><span class="glyphicon glyphicon-list"></span>&nbsp;&nbsp;Détail de la commande n°<?php echo $id; ?></h1>
    <br/><br class="clear"/>
    <br/>
    <div class="bs-example">
        <table id="affiche" class="table table-hover" style="text-align:center !important">
            <thead>
                <tr>
                    <th>#
                    </th>
                    <th>Commande
                    </th>
                    <th>Montant (TTC)
                    </th>
                    <th>Date
                    </th>
                    <th>Id membre  
                    </th>
                    <th>Pseudo
                    </th>
                    <th>Id produit
                    </th>
                    <th>Salle
                    </th>
                    <th>Id salle
                    </th>
                    <th>Ville
                    </th>
                </tr>
            </thead>
            <tbody id="contenuProduit">
    <?php echo $details ?>
            </tbody>
        </table>

    </div>
<?php } ?>  
</div>