<?php
$host	=	'localhost';
$base	=	'users';
$user	=	'root';
$pass	=	'root';

try
{
	$connexion = new PDO("mysql:host=$host;dbname=$base", $user, $pass);
	$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	die('Erreur : '.$e->getMessage());
}
