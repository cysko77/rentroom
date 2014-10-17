<div class="col-xs-12 padLeft0">
    <h1><span class="glyphicon glyphicon-star"></span>&nbsp;Nous Contacter:</h1>
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
        <div class="pull-left col-xs-8 pad0">
            <form id="formContact" role="form" class="form-horizontal" action="index.php?controller=statique&action=contact" method="post">
              <?php if(!$connected){?>
              <?php $error = $data->erreur($dataReponse, "nom"); ?>
              <div class="form-group <?php echo $error[0]; ?> has-feedback">
                <div class="col-lg-10">
                  <label for="inputText1" class="control-label">Nom</label><span class="red">*</span>
                  <input name="nom" type="text" placeholder="Nom" id="inputText1" class="form-control" value="<?php echo $data->valeur($dataReponse, "nom"); ?>">
                  <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
                </div>
              </div>
              
              <?php $error = $data->erreur($dataReponse, "prenom"); ?>
              <div class="form-group <?php echo $error[0]; ?> has-feedback">
                <div class="col-lg-10">
                  <label for="inputText2" class="control-label">Prénom</label><span class="red">*</span>
                  <input name="prenom" type="text" placeholder="Prénom" id="inputText2" class="form-control" value="<?php echo $data->valeur($dataReponse, "prenom"); ?>">
                  <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
                </div>
              </div>  
                
              <?php $error = $data->erreur($dataReponse, "email"); ?>
              <div class="form-group <?php echo $error[0]; ?> has-feedback">
                  <div class="col-lg-10">
                    <label for="inputEmail1" class="control-label">Email</label><span class="red">*</span>
                    <input name="email" type="text" placeholder="Email" id="inputEmail1" class="form-control" value="<?php echo $data->valeur($dataReponse, "email"); ?>">
                    <span class="glyphicon glyphicon-<?php echo $error[1]; ?> form-control-feedback"></span>
                  </div>
              </div>
              <?php } ?>
    
              <?php $error = $data->erreur($dataReponse, "message"); ?>
              <div class="form-group <?php echo $error[0]; ?>">
                <div class="col-lg-10">
                  <label for="inputText1" class="control-label">Message</label><span class="red">*</span>
                  <textarea placeholder="Votre message..." rows="10" class="form-control" name="message"><?php echo $data->valeur($dataReponse, "message"); ?></textarea>
                </div>
              </div>
              <div class="form-group">
                <div class="col-lg-10">
                  <button class="btn btn-warning" type="submit">Envoyer</button>
                </div>
              </div>
            </form>

        </div>
    <script type="text/javascript"> 
        var long = 2.3082500000000437;
        var lat =  48.869576;
    </script>
    
    
        <div class="pull-right col-xs-4 pad0">
            <div  style="background-color: #fefefe; padding:10px;margin-top:20px; border: 1px solid #e0e0e0">
            <h4 class="img-h"><img alt="logo_lokisalle" src="lokisalle/images/logo.jpg"></h4>
            <address>
              Avenue des Champs-Élysées<br>
             75008 Paris<br>
              <abbr>Tél:</abbr> (+33) 1.02.03.04.05
            </address>
            <br style="clear:both">
            <div id="map-canvas" style="width:100%; height:300px; background:#cecece"></div>
          </div>
            <br style="clear:both"> <br>
        </div>
</div>
