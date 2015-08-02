<?php
//On se connecte a la base
include 'db.inc.php';
$req = $connexion->prepare('SELECT * FROM inscription ORDER BY id DESC;');
$req->execute();
// Renvoi de la valeur de retour d'execution de l'ajout pour savoir si c'est ok ou pas
while ($resultat = $req->fetch(PDO::FETCH_ASSOC)) {
	$actif = ($resultat['actif'] != 0)?'<span class="label label label-success">Actif</span>':'<span class="label label label-danger">Inactif</span>';
	echo "	<tr>
				<td>{$resultat['firstname']} {$resultat['lastname']}</td>
				<td>{$resultat['pseudo']}</td>
				<td>{$resultat['email']}</td>
				<td>{$resultat['phone']}</td>
				<td>{$resultat['category']}</td>
				<td>{$resultat['birthday']}</td>
				<td>{$resultat['inscription']}</td>
				<td>{$actif}</td>
			</tr>
	";	
}
?>