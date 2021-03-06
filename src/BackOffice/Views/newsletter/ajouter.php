<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Créer une newsletter</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=newsletter&action=afficher" class="btn btn-default">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=newsletter&action=ajouter"  class="btn btn-warning">Créer</a>
        </div>
    </div>
</div>
<br class="clear"/>
<br/>

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

<form class="marginTop30" action="index.php?controller=newsletter&action=ajouter" method="post">
   
   <div class="col-xs-6" style="padding-left:0">
        
        <?php $error = $data->erreur($dataReponse, "titre"); ?> 
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="titre" class="control-label">Titre</label><span class="red">*</span>
            <input type="text" name="titre" class="form-control" id="titre" placeholder="Titre" value="<?php echo $data->valeur($dataReponse, "titre"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>
        
        
        
    </div>
   <div class="col-xs-12" style="padding:0">
         
        <?php $error = $data->erreur($dataReponse, "contenu"); ?>
        <div id="ontenu" class="form-group <?php echo $error[0]; ?>" style="margin-bottom:14px">
            <label for="contenu" class="control-label">Message</label><span class="red">*</span>
            <textarea name="contenu" class="form-control" rows="16" id="content" placeholder="Votre message" ><?php echo $data->valeur($dataReponse, "contenu"); ?></textarea>
            
        </div>
    
    </div>



    <div class="form-group pull-left" style="margin-top:25px">
        <button type="submit" class="btn btn-warning">Enregistrer la newsletter</button>
    </div>
    <br class="clearfix"/>
</form>
<br/>
</div>