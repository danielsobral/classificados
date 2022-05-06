<?php require 'config.php'; ?>
<?php use \classes\Usuario; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classificados</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/script.js"></script>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="./" class="navbar-brand">Classificados</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <?php if (isset($_SESSION['cLogin']) && !empty($_SESSION['cLogin'])) : ?>
                    <li><a href="#">
                        <?php
                        require 'classes/Usuario.php';
                        $u = new Usuario();
                        $nomeUsuario = $u->getNome();
                        echo "Bem-vindo ".$nomeUsuario['nome'];
                        ?>
                    </a></li>
                    <li><a href="meus-anuncios.php">Meus Anúncios</a></li>
                    <li><a href="sair.php">Sair</a></li>
                <?php else : ?>
                    <li><a href="cadastre-se.php">Cadastre-se</a></li>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>