<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Panier d'achat</h1><br/>

<?php if ($connected)
{
    ?>
    <div class="col-xs-4 pad0 pull-right" style="margin-top:5px">
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <a href="index.php?controller=produit&action=viderPanier" class="btn btn-danger delete" data="de vider votre panier"><span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Vider le panier</a>
            </div>
            <div class="btn-group">
                <a href="index.php?controller=produit&action=accueil"  class="btn btn-warning" ><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;&nbsp;Continuer vos achats</a>
            </div>
        </div>
    </div>
<?php } ?>
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

<?php if ($connected && !empty($produits))
{
    ?>
    <div class="bs-example">
        <p>Si vous disposez d'un code promotionnel, veuillez le saisir ci-dessous.</p>
        <br/>
        <form id="promo" class="form-inline" method="post" action="index.php?controller=produit&action=promotionPanier">
            <div class="form-group">
                <label for="codePromo">Code promotionnel: </label>
                <input style="border-radius:0" type="text" name="code_promo" class="form-control" id="codePromo" placeholder="Votre code...">
            </div>
            <button style="border-radius:0" type="submit" class="btn btn-default">ok</button>
        </form>
        <br class="clear"/>
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
                    <th>Supprimer</th>
                    <th>Prix HT </th>

                </tr>
            </thead>
            <tbody id="contenuProduit">
    <?php echo $produits ?>
            </tbody>
        </table>
        <form id="paher" method="post" action="index.php?controller=produit&action=paiement">
            <div class="checkbox">
                <label>
                    J'accepte les conditions générales de vente<span class="red">*</span> <a href="index.php?controller=statique&action=conditions" target="_bank">(voir)</a>&nbsp; <input name="cgv" type="checkbox"> 
                </label>
            </div>
            <div style="margin-top:10px">
                <button style="border-radius:0" type="submit" class="btn btn-warning pull-right" name="payer">Passer la commande</button>
            </div>
        </form>
    </div><br class="clear"/><br/>
    <p style="background:#f7f7f7;padding:10px; color:#666">
        Tous nos articles sont calculés avec le taux de TVA à 20%<br/>
        <strong>Réglement par chéque uniquement.</strong><br/>
        Nous attendons votre réglement par chéque à l'adresse suivante:<br/>
        Ma boutique 1 Rue Boswellia, 75000 Paris, France.<br/>
        Si nos ne recevons pas le chéque sous 7 jours ouvrables , nous nous laisons le droit d'annuler cette commande.
    </p>
<?php } ?>
</div>