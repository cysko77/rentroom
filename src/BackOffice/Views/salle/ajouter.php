<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Ajouter une salle</h1><br/>
<div class="col-xs-2 pad0 pull-right" style="margin-top:5px">
    <div class="btn-group btn-group-justified">
        <div class="btn-group">
            <a href="index.php?controller=salle&action=afficher" class="btn btn-default">Afficher</a>
        </div>
        <div class="btn-group">
            <a href="index.php?controller=salle&action=ajouter"  class="btn btn-warning">Ajouter</a>
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

<form action="" method="post" ENCTYPE="multipart/form-data">


    <div class="col-xs-6" style="padding-left:0">
        <?php $error = $data->erreur($dataReponse, "titre"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="titre" class="control-label">Titre</label><span class="red">*</span>
            <input type="text" name="titre" class="form-control" id="prix" placeholder="Titre" value="<?php echo $data->valeur($dataReponse, "titre"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "description"); ?>
        <div id="description" class="form-group <?php echo $error[0]; ?>" style="margin-bottom:6px">
            <label for="description" class="control-label">Description</label><span class="red">*</span>
            <textarea name="description" class="form-control" rows="8" id="textareaDescription" placeholder="Description" style="height:191px"><?php echo $data->valeur($dataReponse, "description"); ?></textarea>
        </div>

        <?php $error = $data->erreur($dataReponse, "capacite"); ?>
        <div class="form-group <?php echo $error[0]; ?> has-feedback" style="margin-top:15px">
            <label for="capacite" class="control-label">Capacité</label><span class="red">*</span>
            <input type="text" name="capacite" class="form-control" id="capacite" placeholder="Capacite" value="<?php echo $data->valeur($dataReponse, "capacite"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>


        <?php $error = $data->erreur($dataReponse, "categorie"); ?>
        <div class="form-group <?php echo $error[0]; ?>" >
            <label for="categorie" class="control-label">Catégorie</label><span class="red">*</span>
            <select name="categorie" class="form-control" id="categorie">
                <option value="" <?php
                if ($data->valeur($dataReponse, "categorie") === null)
                {
                    echo "selected";
                }
                ?> >Sélectionnez une catégorie</option>
                <option value="Reunion" <?php
                if ($data->valeur($dataReponse, "categorie") === "Reunion")
                {
                    echo "selected";
                }
                ?> >Réunion</option>
                <option value="Mariage" <?php
                if ($data->valeur($dataReponse, "categorie") === "Mariage")
                {
                    echo "selected";
                }
                ?> >Mariage</option>
                <option value="Ceminaire" <?php
                if ($data->valeur($dataReponse, "categorie") === "Ceminaire")
                {
                    echo "selected";
                }
                ?> >Céminaire</option>
            </select>
        </div>

    </div>


    <div class="col-xs-6 " style="padding-right:0;">
        <?php $error = $data->erreur($dataReponse, "photo"); ?>
        <div id="divInputImg" class="form-group <?php echo $error[0]; ?>">
            <div class="col-xs-12 pad0" >
                <label for="titre" class="control-label">Photo</label>
            </div>
            <div class="col-xs-12 form-control" style="height:165px; margin-bottom: 15px">
                <div id="divPhoto" class="col-xs-6"><img id="uploadImg" src="lokisalle/images/salles/miniature/noImg.jpg<?php echo $data->valeur($dataReponse, "photo"); ?>" alt="<?php echo $data->valeur($dataReponse, "photo"); ?>"></div>
                <div class="col-xs-6" style="height:155px;margin-top:5px">
                    <div id="file-uploader" data="salle">		
                    </div>
                    <input type="file" name="qqfile" id="qqfile" placeholder="Titre" value="">
                    <input type="hidden" name="photo" id="photo" value="<?php echo $data->valeur($dataReponse, "photo"); ?>">
                </div>
            </div>

        </div>

        <?php $error = $data->erreur($dataReponse, "adresse"); ?>
        <div id="adresse" class="form-group <?php echo $error[0]; ?> marginTop" style="margin-bottom:6px">
            <label for="adresse" class="control-label">Adresse</label><span class="red">*</span>
            <textarea name="adresse" class="form-control" rows="2" id="textareaAdresse" placeholder="Adresse" style="height:70px"><?php echo $data->valeur($dataReponse, "adresse"); ?></textarea>
        </div>

        <?php $error = $data->erreur($dataReponse, "cp"); ?>
        <div id="cpVilleSalle" class="form-group <?php echo $error[0]; ?> has-feedback">
            <label for="cp" class="control-label">Code postal</label><span class="red">*</span>
            <input type="text" name="cp"class="form-control" id="inputCpVilleSalle" value="<?php echo $data->valeur($dataReponse, "cp"); ?>" autocomplete="off" placeholder="Code postal">
            <span id="spanCpVilleSalle" class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <?php $error = $data->erreur($dataReponse, "ville_france_id"); ?>		
        <div id="ville_france_id_VilleSalle" class="form-group <?php echo $error[0]; ?>" style="margin-bottom:30px">
            <label for="ville_france_id" class="control-label">Ville</label><span class="red">*</span><img id="loader_cp" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
            <input name="ville_france_id" class="form-control" id="VilleSalle" value="<?php echo $data->valeur($dataReponse, "ville_france_id"); ?>">
            <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
        </div>

        <input type="hidden" name="lat_deg" id="lat" value="<?php echo $data->valeur($dataReponse, "lat_deg"); ?>">
        <input type="hidden" name="long_deg" id="lng" value="<?php echo $data->valeur($dataReponse, "long_deg"); ?>">

    </div>


    <div class="form-group pull-right" style="margin-top:25px">
        <button type="submit" class="btn btn-warning">Ajouter</button>
    </div>
    <br class="clearfix"/>

</form>
<br/>
</div>
