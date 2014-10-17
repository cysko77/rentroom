<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Inscription</h1><br/>
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


<form id="form_inscrip"  method="POST" action="index.php?controller=membre&action=inscription" ENCTYPE="multipart/form-data">
    <div class="col-xs-6"  style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "pseudo"); ?>
        <div id="pseudo" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="pseudo" class="control-label">Pseudo<span class="red">*</span><span class="spe"> 3 caractères min.</span></label>
            <input type="text" name="pseudo" class="form-control" id="inputPseudo1" value="<?php echo $data->valeur($dataReponse, "pseudo"); ?>" placeholder="Pseudo">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "mdp"); ?> 
        <div id="mdp" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="mdp" class="control-label">Mot de passe<span class="red">*</span><span class="spe"> entre 3 & 15 caractères</span></label>
            <input type="password" name="mdp" class="form-control" id="inputPassword" value="<?php echo $data->valeur($dataReponse, "mdp"); ?> " placeholder="Mot de passe">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "nom"); ?> 
        <div id="nom"class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="nom" class="control-label">Nom</label><span class="red">*</span>
            <input type="text" name="nom" class="form-control" id="inputNom" value="<?php echo $data->valeur($dataReponse, "nom"); ?> " placeholder="Nom">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "prenom"); ?>
        <div id="prenom" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="prenom" class="control-label">Prénom</label><span class="red">*</span>
            <input type="text" name="prenom" class="form-control" id="inputPrenom" value="<?php echo $data->valeur($dataReponse, "prenom"); ?>" placeholder="Prénom">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>


        <?php $error = $data->erreur($dataReponse, "email"); ?>
        <div id="email"class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="email" class="control-label">Email</label><span class="red">*</span>
            <input type="text" name="email" class="form-control" id="inputEmail" value="<?php echo $data->valeur($dataReponse, "email"); ?>" placeholder="Email" autocomplete="off" >
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "sexe"); ?>
        <div id="sexe"class="form-group <?php echo $error[0]; ?>">
            <label for="sexe" class="control-label">Votre sexe</label><span class="red">*</span>
            <select name="sexe" class="form-control" id="Selectsexe">
                <option value="" <?php
                if ($data->valeur($dataReponse, "sexe") === "")
                {
                    echo "selected";
                }
                ?> >Selectionnez votre sexe</option>
                <option value="Homme" <?php
                if ($data->valeur($dataReponse, "sexe") === "Homme")
                {
                    echo "selected";
                }
                ?> >Homme</option>
                <option value="Femme" <?php
                if ($data->valeur($dataReponse, "sexe") === "Femme")
                {
                    echo "selected";
                }
                ?> >Femme</option>
            </select>
        </div>

    </div>



    <div class="col-xs-6"  style="padding-right:0"> 

        <?php $error = $data->erreur($dataReponse, "adresse"); ?>
        <div id="adresse" class="form-group <?php echo $error[0]; ?>" style="margin-bottom:14px">
            <label for="adresse" class="control-label">Adresse</label><span class="red">*</span>
            <textarea name="adresse" class="form-control" rows="8" id="textareaAdresse" style="height:183px" placeholder="Votre adresse"><?php echo $data->valeur($dataReponse, "adresse"); ?></textarea>
        </div>


        <?php $error = $data->erreur($dataReponse, "cp"); ?> 
        <div id="cp" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="cp" class="control-label">Code postal</label><span class="red">*</span>
            <input type="text" name="cp"class="form-control" id="inputCp" value="<?php echo $data->valeur($dataReponse, "cp"); ?> " autocomplete="off" placeholder="Code postal">
            <span id="spanCp" class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "ville_france_id"); ?>		
        <div id="ville_france_id" class="form-group <?php echo $error[0]; ?> has-feedback" style="margin-bottom:37px">
            <label for="ville_france_id" class="control-label">Ville</label><span class="red">*</span><img id="loader_cp" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
            <input name="ville_france_id" class="form-control" id="inputVille" placeholder="La ville" value="<?php echo $data->valeur($dataReponse, "ville_france_id"); ?>">
            <span id="spanVille" class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <div class="form-group">
            <span style="font-size:10px;display:block">Voulez-vous recevoir les newsletters mensuels et être informer des dernières promotions?</span>
            <label>
                <input type="radio" name="statut_newsletter" id="optionsRadios2" value="0" <?php echo ($data->valeur($dataReponse, "statut_newsletter") == "0" || empty($dataReponse) ) ? "checked" : true; ?> >
                Non
            </label>
            <label>
                <input type="radio" name="statut_newsletter" id="optionsRadios1" value="1" <?php echo ($data->valeur($dataReponse, "statut_newsletter") == "1") ? "checked" : true; ?>  >
                Oui
            </label>

        </div>

        <div class="form-group pull-right" style="margin-top:25px">
            <button type="submit" class="btn btn-warning">Inscription</button>
        </div>
        <br class="clearfix"/>
    </div>

</form>
<br/>
</div>