
<div class="jaune"></div>
<div style="position:relative" class="container marketing">
    <img src="lokisalle/images/logo.jpg" alt="logo_lokisalle">
    <!-- Menu ===========================================================================================================================-->
    <ul id="topnav">
        <?php if (!$request->isConnected())
        {
            ?> <li style="width:90px; height:40px; border:none; background:#fff"></li> <?php } ?>
        <li id="accueil"><a href="index.php">Accueil</a></li>
        <li><a href="index.php?controller=produit&action=reservation">Réservation</a></li>
        <li><a href="index.php?controller=produit&action=recherche">Recherche</a></li>
        <?php if ($request->isConnected())
        {
            ?>
            <li>

                <a href="index.php?controller=membre&action=profil"  style="background:#FFB643;">Votre profil</a>

    <?php if ($request->isAdmin())
    {
        ?> <!-- Début menu admin-->
                    <div class="sub">
                        <span style="float:left">
                            <a class="first" href="index.php?controller=salle&action=afficher">&lowast; Gestion des salles</a>
                            <a class="first" href="index.php?controller=avis&action=afficher">&lowast; Gestion des avis</a>

                        </span>

                        <span style="float:left">
                            <a class="first" href="index.php?controller=produit&action=afficher">&lowast; Gestion des produits</a>
                            <a class="first" href="index.php?controller=promotion&action=afficher">&lowast; Gestion des codes promo</a>

                        </span>

                        <span style="float:left">
                            <a class="first" href="index.php?controller=membre&action=afficher">&lowast; Gestion des membres</a>
                            <a class="first" href="index.php?controller=statistique&action=afficher">&lowast; Les statistiques</a>

                        </span>

                        <span id="pro" style="float:left">
                            <a class="first" href="index.php?controller=commande&action=afficher">&lowast; Gestion des commandes</a>
                            <a class="first" href="index.php?controller=newsletter&action=afficher">&lowast; Envoyer une newsletter</a>

                        </span>

                    </div>
            <?php } ?> <!-- Fin menu admin-->

            </li>
<?php } ?> <!-- Fin menu membre-->
    </ul>

    <!-- Menu ===========================================================================================================================-->


    <div style="position:absolute; top:0px; right:0px" class="pull-right" id="header">
        <img style="position:absolute;right:-5px;top:0"src="lokisalle/images/coin_right.jpg" alt="coin">
        <img style="position:absolute;right:5px;top:50px"src="lokisalle/images/aide.jpg" alt="aide">
<?php if (!$connected)
{
    ?>
            <!-- Button inscription -->
            <a id="linkInscription" onclick="return false;" class="btn colorRouge pull-right" data-toggle="modal" data-target="#myModalInscription" href="index.php?controller=membre&action=inscription">
                Inscription
            </a>
            <!-- Button Connexion -->
            <a id="linkConnexion" onclick="return false;" class="btn colorJaune pull-right" data-toggle="modal" data-target="#myModalConnexion" href="index.php?controller=membre&action=connexion">
                Connexion
            </a>
            <?php
        }
        else
        {
            $dataSession = $request->getParameters("SESSION"); ?>
            <!-- Button inscription -->
            <a onclick="return false;" id="deconnexion" class="btn colorRouge pull-right" href="index.php?controller=membre&action=deconnexion">
                Déconnexion
            </a>

            <!-- Button Panier -->
            <a class="btn btn colorJaune" style="padding:1px 16px;border:none;text-decoration:none;height:40px" href="index.php?controller=produit&action=panier" class="pull-right">

                <p class="marg0" style="text-align:center;font-size:11px;line-height:12px; color:#666;font-family: arial;border-bottom:1px solid #fff;padding-bottom:3px"><span id="articles"><?php echo (isset($dataSession['panier']['boutton'])) ? $dataSession['panier']['boutton']['nombreProduit'] : 0; ?></span><span> article(s) </span></p>
                <p class="marg0 " style="text-align:center;color:#fff"><span id="prix"><?php echo (isset($dataSession['panier']['boutton'])) ? $dataSession['panier']['boutton']['montant'] : 0; ?></span><span id="prix" class="glyphicon glyphicon-euro" style="font-size:11px"></span></p>
            </a>
<?php } ?>
    </div>
</div>

<!-- /END header -->

<!-- Carousel
================================================== -->
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="item active">
            <img src="<?php echo WEBROOT; ?>web/lokisalle/images/1.jpg" alt="First slide">
            <!-- contenu slider
            <div class="container">
              <div class="carousel-caption">
                <h1>Example headline.</h1>
                <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
              </div>
            </div>
            -->
        </div>
        <div class="item">
            <img src="<?php echo WEBROOT; ?>web/lokisalle/images/2.jpg" alt="Second slide">
            <!-- contenu slider
            <div class="container">
              <div class="carousel-caption">
                <h1>Another example headline.</h1>
                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
              </div>
            </div>
            -->
        </div>
        <div class="item">
            <img src="<?php echo WEBROOT; ?>web/lokisalle/images/3.jpg" alt="Third slide">
            <!-- contenu slider
            <div class="container">
              <div class="carousel-caption">
                <h1>One more for good measure.</h1>
                <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
                <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
              </div>
            </div>
            -->
        </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div>
<!-- /.carousel -->



