
<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Votre profil</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=membre&action=profil" class="btn btn-warning">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=membre&action=modifier"  class="btn btn-default">Modfifier</a>
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


<div class="col-xs-12 pad0">
    <div class="col-xs-3" style="padding-left:0;">
        <img src="../web/lokisalle/images/membres/<?php echo $data->valeur($dataReponse, "photo"); ?>" width="250" alt="profil"/>
    </div>
    <div class="col-xs-4" style="height:232px;border-right:1px dashed #cecece">
        <h3 class="marg0" style="margin-bottom:15px;color:#ED9C28" ><?php echo ucfirst($data->valeur($dataReponse, "prenom")) . " " . mb_strtoupper($data->valeur($dataReponse, "nom"), 'UTF-8'); ?></h3>
        <p class="col-xs-12 pad0" style="border-bottom:1px dashed #cecece; margin-bottom:10px;padding-bottom:10px" >
            <span class="glyphicon glyphicon-map-marker pull-left">&nbsp;</span>
            <span class="pull-left"><?php echo ucfirst($data->valeur($dataReponse, "adresse")); ?><br/>
                <?php echo $data->valeur($dataReponse, "cp"); ?> <br/><?php echo ucfirst($data->valeur($dataReponse, "nom_reel")); ?>
            </span>
        </p>
        <p class="col-xs-12 pad0"style="border-bottom:1px dashed #cecece; margin-bottom:10px;padding-bottom:10px" >
            <span class="glyphicon glyphicon-user pull-left">&nbsp;</span>
            <span class="pull-left"><?php echo $data->valeur($dataReponse, "pseudo"); ?><br/>
            </span>
        </p>
        <p class="col-xs-12 pad0">
            <span class="glyphicon glyphicon-send pull-left">&nbsp;</span>
            <span class="pull-left"><?php echo $data->valeur($dataReponse, "email"); ?><br/>
            </span>
        </p>
    </div>
    <div class="col-xs-5 " style="padding-right:0">
        <h3 class="marg0"style="margin-bottom:7px"><span class="glyphicon glyphicon-list"></span>&nbsp;Vos dernières commandes</h3>
        <?php if ($commande)
        {
            ?>
            <div class="bs-example">
                <table id="affiche" class="table table-hover" style="text-align:center !important; margin-bottom: 0px; margin-top:5px">
                    <thead>
                        <tr>
                            <th>Numéro&nbsp;&nbsp;
                            </th>
                            <th>Date&nbsp;&nbsp;
                            </th>
                            <th>Prix&nbsp;&nbsp;
                            </th>
                            <th>Statut&nbsp;&nbsp;  
                            </th>
                    <thead>
    <?php echo $commande; ?>

                </table>
            <?php echo$pagination; ?>
            </div>
            <?php
        }
        else
        {
            echo "Vous n'avez pas encore passé de commande.";
        }
        ?>
    </div>
    <img src="../web/lokisalle/images/bg.png"  alt="profil"/>


</div>
</div>