
<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Connexion</h1><br/>
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


<form id="form_conn"  method="POST" action="index.php?controller=membre&action=connexion" ENCTYPE="multipart/form-data">
    <div class="col-xs-6"  style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "reponse"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="pseudo" class="control-label">Pseudo<span class="red">*</span></label><br/>
            <input type="text" autocomplete="off" class="form-control" name="pseudo" id="inputPseudo" value="<?php echo $data->valeur($dataReponse, "pseudo"); ?>" placeholder="Pseudo" >
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
            <a onclick="return false;" id="passForgoten" style="font-size:10px" href="index.php?controller=membre&action=passOublie" data-dismiss="modal" data-toggle="modal" data-target="#myModalPass">Vous avez oublié votre mot de passe?</a>
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="rememberMe" <?php if(isset($_COOKIE['USESSIONLOKI'])) {echo "checked";}  ?>> Se souvenir de moi
                </label>
            </div>
        </div>

    </div>



    <div class="col-xs-6"  style="padding-right:0"> 
        <?php $error = $data->erreur($dataReponse, "reponse"); ?> 
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="mdp" class="control-label">Mot de passe<span class="red">*</span></label><br/>
            <input type="password" class="form-control" name="mdp" id="inputPassword" value="<?php echo $data->valeur($dataReponse, "mdp"); ?>" placeholder="Mot de passe">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>


        <div class="form-group pull-right" style="margin-top:25px">
            <button type="submit" class="btn btn-warning">Inscription</button>
        </div>
        <br class="clearfix"/>
    </div>

</form>
<br/>
</div>