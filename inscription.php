<?php
if ($_POST) {
	//On se connecte a la base
	include 'db.inc.php';
	$birthday = explode('/', $_POST['birthDay']);
	$birthday = $birthday[2].'-'.$birthday[1].'-'.$birthday[0];
	$req = "
	INSERT INTO `inscription` (
		`firstname`, 
		`lastname`, 
		`pseudo`, 
		`email`, 
		`password`, 
		`phone`, 
		`skype`, 
		`category`, 
		`birthday`, 
		`inscription`) 
	VALUES (
		'{$_POST['firstname']}' , 
		'{$_POST['lastname']}' , 
		'{$_POST['pseudo']}' , 
		'{$_POST['email']}' , 
		'".sha1(md5($_POST['pass']))."' , 
		'{$_POST['phone']}' , 
		'{$_POST['skype']}' , 
		'{$_POST['category']}', 
		'{$birthday}' , 
		'".date("Y-m-d H:i:s")."'
		);
	";
	$result = $connexion->exec($req);
	// Renvoi de la valeur de retour d'execution de l'ajout pour savoir si c'est ok ou pas
	echo json_encode($result);
}
else {
	header('Location: ./valid.html');
}

?>
