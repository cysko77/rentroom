<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Modifier une promotion</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=promotion&action=afficher" class="btn btn-default">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=promotion&action=ajouter"  class="btn btn-default">Ajouter</a>
        </div>
    </div>
</div>
<br class="clear"/>

<?php
if (!empty($alert))
{
    ?>
    <br/>
    <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php echo $alert[1]; ?>
    </div>
    <?php
}
?>

<form class="marginTop30" action="" method="post">

    <div class="col-xs-6" style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "code_promo"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="code_promo" class="control-label">Code promotion</label><span class="red">*</span>
            <input type="text" name="code_promo" class="form-control" id="code_promo" placeholder="Code promotionnel" value="<?php echo $data->valeur($dataReponse, "code_promo"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

    </div>

    <div class="col-xs-6" style="padding-right:0">
        <?php $error = $data->erreur($dataReponse, "reduction"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="reduction" class="control-label">Réduction (%)</label><span class="red">*</span>
            <input type="text" name="reduction" class="form-control" id="reduction" placeholder="Réduction" value="<?php echo $data->valeur($dataReponse, "reduction"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>
        <br/>
        <button type="submit" class="btn btn-warning pull-right">Ajouter une promotion</button>
    </div>
    <br class="clearfix"/>
</form>
<br/>
</div>