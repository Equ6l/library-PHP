<?php

// On inclue le fichier de configuration et de connexion a la base de donnees
require_once("../includes/config.php");

// On recupere dans $_POST l email soumis par l'utilisateur
$email = $_GET['email'];

// On verifie que l'email est un email valide (fonction php filter_var)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	// Si ce n'est pas le cas, on fait un echo qui signale l'erreur
	echo json_encode('invalide');
} else {
	// Si c'est bon
	// On prepare la requete qui recherche la presence de l'email dans la table tblreaders
	$query = "SELECT * FROM tblreaders WHERE EmailId = :email";
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':email', $email);

	$stmt->execute();

	// On recupere le resultat de recherche
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	// Si le resultat n'est pas vide. On signale a l'utilisateur que cet email existe deja et on desactive le bouton
	// de soumission du formulaire
	if (!empty($result)) {
		echo json_encode('indisponible');
	} else {
		// Sinon on signale a l'utlisateur que l'email est disponible et on active le bouton du formulaire
		echo json_encode('disponible');
	}
}
