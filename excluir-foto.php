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
    $id_anuncio = $a->deleteFoto($_GET['id']);
}

if(isset($id_anuncio)){
    header("Location: editar-anuncio.php?id=".$id_anuncio);
} else {
    header("Location: meus-anuncios.php");
}
