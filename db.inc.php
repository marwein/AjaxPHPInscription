<?php
$host	=	'localhost';
$base	=	'users';
$user	=	'root';
$pass	=	'root';

try
{
	$connexion = new PDO("mysql:host=$host;dbname=$base", $user, $pass);
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}
