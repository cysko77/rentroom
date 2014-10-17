<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Modifier votre profil</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=membre&action=profil" class="btn btn-default">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=membre&action=modifier&id=<?php echo $_SESSION['Auth']->id; ?>"  class="btn btn-warning">Modfifier</a>
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


<form id="form_mod"  method="POST" action="index.php?controller=membre&action=modifier" ENCTYPE="multipart/form-data">
    <div class="col-xs-6"  style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "pseudo"); ?>
        <div id="pseudo" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="pseudo" class="control-label">Pseudo<span class="red">*</span><span class="spe"> 3 caractères min.</span></label>
            <input type="text" name="pseudo" class="form-control" id="inputPseudo1" value="<?php echo $data->valeur($dataReponse, "pseudo"); ?>" placeholder="Pseudo">
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

        <?php $error = $data->erreur($dataReponse, "statut_newsletter"); ?>
        <div id="statut_newsletter"class="form-group <?php echo $error[0]; ?>">
            <label for="statut_newsletter" class="control-label">Abonnement newslleter</label><span class="red">*</span>
            <select name="statut_newsletter" class="form-control" id="selectNewsletter">
                <option value="" <?php
                if ($data->valeur($dataReponse, "statut_newsletter") === "")
                {
                    echo "selected";
                }
                ?> >Activer ou desactiver votre abonnement</option>
                <option value="0" <?php
                if ($data->valeur($dataReponse, "statut_newsletter") === "0")
                {
                    echo "selected";
                }
                ?> >Ne pas s'abonner à la newsletter</option>
                <option value="1" <?php
                if ($data->valeur($dataReponse, "statut_newsletter") === "1")
                {
                    echo "selected";
                }
                ?> >S'abonner à la newsletter</option>
            </select>
        </div>

    </div>



    <div class="col-xs-6"  style="padding-right:0"> 


        <?php $error = $data->erreur($dataReponse, "photo"); ?>
        <div id="divInputImg" class="form-group <?php echo $error[0]; ?>">
            <div class="col-xs-12 pad0" >
                <label for="titre" class="control-label">Photo</label>
            </div>
            <div class="col-xs-12 form-control" style="height:165px; margin-bottom: 15px">
                <div id="divPhoto" class="col-xs-3" style="width:140px"><img id="uploadImg" width="140px" src="lokisalle/images/membres/<?php echo $data->valeur($dataReponse, "photo"); ?>" alt="<?php echo $data->valeur($dataReponse, "photo"); ?>"></div>
                <div class="col-xs-8" style="height:155px;margin-top:5px;width:375px">
                    <div id="file-uploader" data="membre">
                    </div>
                    <input type="file" name="qqfile" id="qqfile" placeholder="Titre" value="">
                    <input type="hidden" name="photo" id="photo" value="<?php echo $data->valeur($dataReponse, "photo"); ?>">
                </div>
            </div>

        </div>

        <?php $error = $data->erreur($dataReponse, "adresse"); ?>
        <div id="adresse" class="form-group <?php echo $error[0]; ?>" style="margin-bottom:14px">
            <label for="adresse" class="control-label">Adresse</label><span class="red">*</span>
            <textarea name="adresse" class="form-control" rows="8" id="textareaAdresse" style="height:53px"><?php echo $data->valeur($dataReponse, "adresse"); ?></textarea>
        </div>


        <?php $error = $data->erreur($dataReponse, "cp"); ?> 
        <div id="cpVilleMembre" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="cp" class="control-label">Code postal</label><span class="red">*</span>
            <input type="text" name="cp"class="form-control" id="inputCpVilleMembre" value="<?php echo $data->valeur($dataReponse, "cp"); ?>" autocomplete="off" placeholder="Code postal">
            <span id="spanCpVilleMembre" class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "ville_france_id"); ?>		
        <div id="ville_france_id_VilleMembre" class="form-group <?php echo $error[0]; ?>" style="margin-bottom:30px">
            <label for="ville_france_id" class="control-label">Ville</label><span class="red">*</span><img id="loader_cp_VilleMembre" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
            <input name="ville_france_id" class="form-control" id="VilleMembre" value="<?php echo $data->valeur($dataReponse, "ville_france_id"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <div class="form-group pull-right" style="margin-top:25px">
            <button type="submit" class="btn btn-warning">Modifier votre profil</button>
        </div>
        <br class="clearfix"/>
    </div>

</form>
<br/>
</div>