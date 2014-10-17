<?php
setcookie("js", "", time() - 19600);
?>

<!DOCTYPE html>
<html class="noJs" lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo $title; ?>">
        <link rel="shortcut icon" href="<?php echo WEBROOT; ?>web/favicon.ico">
        <title><?php echo $title; ?></title>
        <link href="<?php echo WEBROOT; ?>web/lokisalle/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT; ?>web/lokisalle/css/datetimepicker.css"/>
        <link href="<?php echo WEBROOT; ?>web/lokisalle/css/carousel.css" rel="stylesheet">
    </head>

    <!-- HEADER ========================================================= -->
    <?php include("inc/header.php"); ?>
    <!-- / END HEADER-->


    <!-- CONTAINER ======================================================= -->
    <div class="container marketing bcgMarketing">
        <img style="position:absolute;left:-77px;top:0" src="<?php echo WEBROOT; ?>web/lokisalle/images/shadowLeft.jpg" alt="shadowLeft">
        <img style="position:absolute;right:-70px;top:0" src="<?php echo WEBROOT; ?>web/lokisalle/images/shadowRight.jpg" alt="shadowRight">
        <!-- CONTENT ================================================== -->
        
        <?php echo $content; ?>

        <!-- /END CONTENT-->
    </div>
    <!-- / END CONTAINER -->


    <!-- FOOTER ========================================================-->
    <div class="grey"></div>
    <div class="foot2">
        <div class="container marketing ">
            <footer>
                <div class="col-xs-4">&nbsp;</div>
                <div class="col-xs-4">&nbsp;</div>
                <div class="col-xs-4">&nbsp;</div>
            </footer>
        </div>
    </div>

    <div class="black">
        <div class="container marketing">
            <div class="col-xs-1 marg0"><a class="fleche" href="#"><img src="<?php echo WEBROOT; ?>web/lokisalle/images/fleche.jpg" alt="fleche"></a></div>
            <div class="col-xs-10 marg0  marginTop">
                <ul class="list-inline text-center">
                    <li><a class="link-orange" href="index.php?controller=statique&action=mentions">Mentions légales</a></li>
                    <li> | </li>
                    <li><a class="link-orange" href="index.php?controller=statique&action=conditions">Conditions Générales de Ventes</a></li>
                    <li> | </li>
                    <li><a class="link-orange" href="index.php?controller=statique&action=plan">Plan du site </a></li>
                    <li> | </li>
                    <li><a class="btnPrint link-orange" href='iframes/iframe.html'>Imprimer</a></li>
                    <li> | </li>
                    <li><a class="link-orange" href="index.php?controller=membre&action=newsletter">S’inscrire à la newsletter</a></li>
                    <li> | </li>
                    <li><a class="link-orange" href="index.php?controller=statique&action=contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-xs-1 marg0"><a class="fleche pull-right" href="#"><img src="<?php echo WEBROOT; ?>web/lokisalle/images/fleche.jpg" alt="fleche"></a></div>
            <br class="clear"/>
        </div>
    </div
    <!-- FIN FOOTER-->


    <!-- FORM ==================================================================-->
    <div id="form"></div>
    <!-- END FORM-->


    <?php
    if (!$request->isConnected())
    {
        include __DIR__ . "/inc/formMembre.php";
    }
    ?>
    <!-- JavaScript ============================================================ -->
    <script type ="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/jquery.js"></script>
    <script type ="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/cookieJquery.js"></script>
    <script src="<?php echo WEBROOT; ?>web/lokisalle/js/bootstrap.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCUZLpYBA-G6cRa8paiDm07qlkyhRzC05c&sensor=true" type="text/javascript"></script>
    <script type ="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/datetimepicker.js"></script>
    <script src="<?php echo WEBROOT; ?>web/lokisalle/js/fileuploader.js"></script>
    <script type ="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/lokisalle.js"></script>
    <script type="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/lightbox.js"></script>
    <script type="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/jquery.rater.js"></script>
    <script src="<?php echo WEBROOT; ?>web/lokisalle/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="<?php echo WEBROOT; ?>web/lokisalle/js/jquery.printPage.js"></script>
    <script>
        $(document).ready(function() {
            $(".connectLink").attr("href","#");
            var roxyFileman = '<?php echo WEBROOT; ?>web/lokisalle/ckeditor/plugins/fileman/index.html';
            if(document.getElementById("content")){
                CKEDITOR.replace( 'content', {filebrowserBrowseUrl:roxyFileman, 
                                    filebrowserUploadUrl:roxyFileman,
                                    filebrowserImageBrowseUrl:roxyFileman+'?type=image',
                                    filebrowserImageUploadUrl:roxyFileman+'?type=image'
                }); 
            }
            $(".btnPrint").printPage();
            $("#VilleAccueil").ZipToCity(false);
            $("#VilleInscription").ZipToCity(false);
            $("#VilleSalle").ZipToCity(true);
            $("#VilleMembre").ZipToCity(false);
            $('#star').rater({style: 'basic', curvalue:2}); 
        });
    </script>
</body>
</html>
