<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Félicitation</h1><br/>

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

<br class="clear"/>
<div class="bs-example">
    <p>Ci-dessous, le récapitulatif de votre commande.</p>
    <br/>

    <table id="affiche" class="table table-hover" style="text-align:center !important">
        <thead>
            <tr>
                <th>#</th>
                <th>Salle</th>
                <th>Photo</th>
                <th>Date d'arrivée</th>
                <th>Date de départ</th>
                <th>Capacité</th>
                <th>Catégorie</th>
                <th>Code postal</th>
                <th>Ville </th>
                <th>Prix HT </th>

            </tr>
        </thead>
        <tbody id="contenuProduit">
            <?php echo $produits; ?>
        </tbody>
    </table>

    <div style="margin-top:10px">
        <br/>
        <a style="border-radius:0" href="index.php" class="btn btn-warning pull-right">Retour à l'accueil</a>
    </div>
</div>
</div>