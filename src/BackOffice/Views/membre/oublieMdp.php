<div class="col-xs-12 padLeft0">
<h1 class="pull-left"><span class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;Modifier votre mot de passe</h1><br/>
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

if ($display)
{
    ?> 
    <form id="form_init_pass"  method="POST" action="index.php?controller=membre&action=initPass">
        <div class="col-xs-6"  style="padding-left:0">

            <div class="form-group  has-feedback">
                <label for="mdp2" class="control-label">Nouveau mot de passe<span> (3 à 14 caractères)</span> <span class="red">*</span></label><br/>
                <input type="password" class="form-control" name="mdp2" id="inputPassword2" value="" placeholder="Nouveau mot de passe">
                <span class="glyphicon glyphicon- form-control-feedback"></span>
                <input type="hidden" name="email" id="email" value="<?php echo $data->valeur($dataGet, "email"); ?>">
                <input type="hidden" name="token_mdp" id="token_mdp" value=" <?php echo $data->valeur($dataGet, "token_mdp"); ?>">

            </div>



        </div>



        <div class="col-xs-6"  style="padding-right:0"> 

            <div class="form-group has-feedback">
                <label for="mdp" class="control-label">Reécrire votre mot de passe<span class="red">*</span></label><br/>
                <input type="password" class="form-control" name="mdp" id="inputPassword" value="" placeholder="Reécrire votre mot de passe">
                <span class="glyphicon glyphicon- form-control-feedback"></span>
            </div>


            <div class="form-group pull-right" style="margin-top:25px">
                <button type="submit" class="btn btn-warning">Changer mot de passe</button>
            </div>
            <br class="clearfix"/>
        </div>

    </form>
    <br/>
<?php } ?>
</div>