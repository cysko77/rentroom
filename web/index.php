<?php

session_start();
require_once ("../app/config/setting.php");
// On inclut la function debug (à enlever après production) =======>
// ================================================================>
require_once(__DIR__ . '/../vendor/debug.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use HttpRequest\Request;
use UpdateBDD\updateBDD;
    
// Mise a jour de la BDD
$bdd = new updateBDD();
// On pointe vers le controlleur et la methode en fonction des arguments passés dans L'url
$request = new Request();
$request->getRoute();
?>