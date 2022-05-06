<?php
require 'config.php';
if(empty($_SESSION['cLogin'])){
   header("Location: login.php");
    exit;
}
require 'classes/Anuncio.php';
use \classes\Anuncio;
$a = new Anuncio();

if(isset($_GET['id']) && !empty($_GET['id'])){
    $a->deleteAnuncio($_GET['id']);
}
header("Location: meus-anuncios.php");