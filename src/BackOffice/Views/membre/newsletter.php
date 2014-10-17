<h1><span class="glyphicon glyphicon-star"></span>Inscription newsletter:</h1>
<?php
if (!empty($alert))
        {
            ?>
            <br/>
            <div class="alert alert-<?php echo $alert[0]; ?> alert-dismissable margRight15">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $alert[1]; ?>
            </div>
            <?php
}
?>
