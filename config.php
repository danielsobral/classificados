<?php
session_start();
require 'environment.php';

global $config;
if(ENVIRONMENT == 'development') {
	define("BASE_URL", "http://localhost/B7Web/classificados/");
	define("BASE_FOTOS", "assets/images/anuncios/");
	define("BASE_FOTOS_DEFAULT", "assets/images/default.jpg");
	$config['dbname'] = 'classificados';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';
} else {
	define("BASE_URL", "http://localhost/B7Web/classificados/");
	define("BASE_FOTOS", "assets/images/anuncios/");
	define("BASE_FOTOS_DEFAULT", "assets/images/default.jpg");
	$config['dbname'] = 'classificados';
	$config['host'] = 'localhost';
	$config['dbuser'] = 'root';
	$config['dbpass'] = '';
}

global $db;
try {
	$db = new PDO("mysql:dbname=".$config['dbname'].";host=".$config['host'], $config['dbuser'], $config['dbpass']);
} catch(PDOException $e) {
	echo "ERRO: ".$e->getMessage();
	exit;
}