<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Modifier un produit</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=produit&action=afficher" class="btn btn-default">Afficher</a>
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
        <?php
        echo $alert[1];
        if ($alert[0] === "success")
        {
            echo '<strong><a href="index.php?controller=produit&action=afficher" class="link link-warning"> Retour vers tous les  produits</a></strong>';
        }
        ?>
    </div>
    <?php
}
?>

<form action="" method="post">
    <div class="col-xs-6" style="padding-left:0">
        <h4 class="glyphicon glyphicon-calendar" style="position:absolute; right: 20px;top: 23px; z-index: 100;"></h4>
        <?php $error = $data->erreur($dataReponse, "date_arrivee"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="date_arrivee" class="control-label">Date d'arrivée</label><span class="red">*</span>
            <input type="text" name="date_arrivee" class="form-control" id="date_timepicker_arrivee"  placeholder="Date d'arrivée" value="<?php
            if ($data->valeur($dataReponse, "date_arrivee"))
            {
                $date = new DateTime($data->valeur($dataReponse, "date_arrivee"));
                echo $date->format('d/m/Y H:i');
            }
            ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>
    </div>
    <div class="col-xs-6" style="padding-right:0">
        <h4 class="glyphicon glyphicon-calendar" style="position:absolute; right: 5px;top: 23px; z-index: 100;"></h4>
        <?php $error = $data->erreur($dataReponse, "date_depart"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="date_depart" class="control-label">Date départ</label><span class="red">*</span>
            <input type="text" name="date_depart" class="form-control" id="date_timepicker_depart" placeholder="Date de départ" value="<?php
            if ($data->valeur($dataReponse, "date_depart"))
            {
                $date = new DateTime($data->valeur($dataReponse, "date_depart"));
                echo $date->format('d/m/Y H:i');
            }
            ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>

        </div>
    </div>

    <div class="col-xs-6" style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "salle_id"); ?>
        <div id="inputSalle" class="form-group <?php echo $error[0]; ?>">
            <label for="salle_id" class="control-label">Salle</label><span class="red">*</span><img id="loader_cp" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
            <select name="salle_id" class="form-control" id="salle_id">
                <option value="" <?php
                if ($data->valeur($dataReponse, "salle_id") === null)
                {
                    echo "selected";
                }
                ?> >Sélectionnez une salle</option>
                        <?php
                        foreach ($salle as $values)
                        {
                            ?>
                    <option value="<?php echo $values->id; ?>" <?php
                    if ($data->valeur($dataReponse, "salle_id") == $values->id)
                    {
                        echo "selected";
                    }
                    ?> ><?php echo $values->salle; ?></option>

                    <?php
                }
                ?>
            </select>

        </div>
    </div>
    <div class="col-xs-6" style="padding-right:0">
        <?php $error = $data->erreur($dataReponse, "prix"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="prix" class="control-label">Prix</label><span class="red">*</span>
            <input type="text" name="prix" class="form-control" id="prix" placeholder="Prix" value="<?php echo $data->valeur($dataReponse, "prix"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>
    </div>

    <div class="col-xs-6" style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "etat"); ?>
        <div class="form-group <?php echo $error[0]; ?>">
            <label for="etat" class="control-label">Etat</label><span class="red">*</span>
            <select name="etat" class="form-control" id="etat">

                <option value="0" <?php
                if ($data->valeur($dataReponse, "etat") === "0")
                {
                    echo "selected";
                }
                ?> >Disponible</option>
                <option value="1" <?php
                if ($data->valeur($dataReponse, "etat") === "1")
                {
                    echo "selected";
                }
                ?> >Réservé</option>
            </select>
        </div>
    </div>

    <div class="col-xs-6" style="padding-right:0">
        <?php $error = $data->erreur($dataReponse, "promotion_id"); ?>
        <div class="form-group <?php echo $error[0]; ?>">
            <label for="promotion_id" class="control-label">Code promotion</label>
            <select name="promotion_id" class="form-control" id="promotion_id">
                <option value="" <?php
                if ($data->valeur($dataReponse, "promotion_id") === null)
                {
                    echo "selected";
                }
                ?> >Sélectionnez un code promo</option>
                        <?php
                        foreach ($promotion as $values)
                        {
                            ?>
                    <option value="<?php echo $values->id; ?>" <?php
                    if ($data->valeur($dataReponse, "promotion_id") == $values->id)
                    {
                        echo "selected";
                    }
                    ?> ><?php echo $values->code_promo . " - " . $values->reduction . "%"; ?></option>

                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group pull-right" style="margin-top:25px">
        <button type="submit" class="btn btn-warning">Modifier</button>
    </div>
    <br class="clearfix"/>

</form>
<br/>
</div>