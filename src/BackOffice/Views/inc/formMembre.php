<?php $request->getParameters("COOKIE"); ?>
<!-- DIV CONNEXION
================================================== -->
<div class="modal fade" id="myModalConnexion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form method="POST" action="index.php?controller=membre&action=connexion" id="form_connexion" onsubmit="return false;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Connexion</h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12">
                        <div id="alertConnexion" class="alert" style="display:none">
                            <p id="message"></p>
                        </div>

                        <div class="form-group">
                            <label for="pseudo" class="control-label">Pseudo</label><br/>
                            <input type="text" class="form-control" name="pseudo" id="inputPseudo" value="<?php echo $data->valeur($_COOKIE, 'USESSIONLOKI'); ?>" placeholder="Pseudo">
                            <span class="glyphicon"></span>
                        </div>


                        <div class="form-group">
                            <label for="mdp" class="control-label">Mot de passe</label><br/>
                            <input type="password" class="form-control" name="mdp" id="inputPassword" value="<?php echo $data->valeur($_COOKIE, 'USESSIONLOKI'); ?>" placeholder="Mot de passe">
                            <span class="glyphicon"></span>
                            <a id="passForgoten" style="font-size:10px" href="#" data-dismiss="modal" data-toggle="modal" data-target="#myModalPass">Vous avez oublié votre mot de passe?</a>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="rememberMe" <?php if(isset($_COOKIE["USESSIONLOKI"])) {echo "checked=checked";}  ?> > Se souvenir de moi
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <br class="clear"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-warning" id="connexion" data-loading-text="Patientez..." >Connexion</button>
                </div>

            </div>
        </div>
    </form>
</div>
<!-- END DIV CONNEXION -->


<!-- DIV's SUBSCRIPTION 
================================================== -->
<div class="modal fade" id="myModalInscription" tabindex="-1" role="dialog" aria-labelledby="myModalInscriptionLabel" aria-hidden="false">
    <form id="form_inscription"  method="POST" action="index.php?controller=membre&action=inscription" onsubmit="return false;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Inscription</h4>
                </div>
                <div class="modal-body">
                    <div id="alertInscription" class="form-group alert" style="display:none;margin-left:15px;margin-right:15px">
                        <p id="messageInscription"></p>
                    </div>
                    <div class="col-xs-6">

                        <div id="pseudo" class="form-group">
                            <label for="pseudo" class="control-label">Pseudo<span class="red">*</span><span class="spe"> 3 caractères min.</span></label>
                            <input type="text" name="pseudo" class="form-control" id="inputPseudo1" value="" placeholder="Pseudo">
                            <span class="glyphicon"></span>
                        </div>


                        <div id="mdp" class="form-group">
                            <label for="mdp" class="control-label">Mot de passe<span class="red">*</span><span class="spe"> entre 3 & 15 caractères</span></label>
                            <input type="password" name="mdp" class="form-control" id="inputPassword" value="" placeholder="Mot de passe">
                            <span class="glyphicon"></span>
                        </div>


                        <div id="nom" class="form-group">
                            <label for="nom" class="control-label">Nom</label><span class="red">*</span>
                            <input type="text" name="nom" class="form-control" id="inputNom" value="" placeholder="Nom">
                            <span class="glyphicon"></span>
                        </div>

                        <div id="prenom" class="form-group">
                            <label for="prenom" class="control-label">Prénom</label><span class="red">*</span>
                            <input type="text" name="prenom" class="form-control" id="inputPrenom" value="" placeholder="Prénom">
                            <span class="glyphicon"></span>
                        </div>


                        <div id="email" class="form-group">
                            <label for="email" class="control-label">Email</label><span class="red">*</span>
                            <input type="text" name="email" class="form-control" id="inputEmail" value="" placeholder="Email" autocomplete="off" >
                            <span class="glyphicon"></span>
                        </div>

                        <div id="sexe" class="form-group">
                            <label for="sexe" class="control-label">Votre sexe</label><span class="red">*</span>
                            <select name="sexe" class="form-control" id="Selectsexe">
                                <option value="">Sélectionnez votre sexe</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-xs-6"> 


                        <div id="adresse" class="form-group" style="margin-bottom:15px">
                            <label for="adresse" class="control-label">Adresse</label><span class="red">*</span>
                            <textarea name="adresse" class="form-control" rows="8" id="textareaAdresse" style="height:183px" placeholder="Votre adresse"></textarea>
                        </div>


                        <div id="cpVilleInscription" class="form-group has-feedback">
                            <label for="cp" class="control-label">Code postal</label><span class="red">*</span>
                            <input type="text" name="cp" class="form-control" id="inputCpVilleInscription" value="" autocomplete="off" placeholder="Code postal">
                            <span id="spanCpVilleInscription" class="glyphicon"></span>
                        </div>


                        <div id="ville_france_id_VilleInscription" class="form-group" style="margin-bottom:30px">
                            <label for="ville_france_id" class="control-label">Ville</label><span class="red">*</span><img id="loader_cp_VilleInscription" style="display:none" src="lokisalle/images/294.GIF" alt="loader_cp"/>
                            <input name="ville_france_id" class="form-control" id="VilleInscription">

                        </div>

                        <div class="form-group">
                            <span style="font-size:10px;display:block">Voulez-vous recevoir les newsletters mensuels et être informer des dernières promotions?</span>
                            <label>
                                <input type="radio" name="statut_newsletter" id="optionsRadios2" value="0" checked>
                                Non
                            </label>
                            <label>
                                <input type="radio" name="statut_newsletter" id="optionsRadios1" value="1" >
                                Oui
                            </label>

                        </div>

                    </div>

                </div>
                <br class="clear"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button id="inscription" type="submit" class="btn btn-warning">S'inscrire</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END DIV's SUBSCRIPTION -->

<!-- DIV's pass
================================================== -->
<div class="modal fade" id="myModalPass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="form_mdp" method="POST" action="index.php?controller=membre&action=passOublie" onsubmit="return false;" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Mot de passe oublié</h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12">
                        <div id="reponsePass" class="alert" style="display:none">
                            <p id="messagePass" style="margin:0 !important"></p>
                        </div>
                        <p>Afin de pouvoir réinitialiser votre mot de passe, vous devez renseignez votre email.</p>
                        <div class="form-group">
                            <label for="email" class="control-label">Votre Email</label>
                            <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" autocomplete="off">
                            <span class="glyphicon glyphicon form-control-feedback"></span>
                        </div>

                    </div>
                </div>
                <br class="clear"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-warning">Envoyer</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END DIV's pass-->
