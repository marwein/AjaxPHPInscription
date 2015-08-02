<?php
if ($_POST) {
	//On se connecte a la base
	include 'db.inc.php';
	
	//On extrait le nombre de ligne de la recherche du maildans la base, 0 s'il n'existe pas
	$nb = $connexion->query("SELECT COUNT(*) FROM inscription WHERE email = '{$_POST['email']}';")->fetchColumn();
	//$estDisponible prendra faux si le resultat de la requete contient une ligne ou plus, sinon il restera a vrai
	$estDisponible = (($nb > 0)?false:true);
	echo json_encode(
		array(
			'valid' => $estDisponible,
		));
	exit;
}
else {
	header('Location: ./valid.html');
}
?>
