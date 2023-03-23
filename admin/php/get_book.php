<?php
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero ISBN du livre*/
require_once("../includes/config.php");

if (!empty($_GET['bookid'])) {

	$ISBN = strtoupper($_GET['bookid']);

	// On prepare la requete de recherche du livre correspondant
	$sql = "SELECT * FROM tblbooks WHERE ISBNNumber = :isbn";
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':isbn', $ISBN);
	// On execute la requete
	$stmt->execute();

	$result = $stmt->fetch(PDO::FETCH_OBJ);

	if (!empty($result)) {
		// Si un resultat est trouve
		// On affiche le nom du livre
		// On active le bouton de soumission du formulaire
		echo "Livre : " . $result->BookName;
	} else {
		// Sinon
		// On affiche que "ISBN est non valide"
		// On desactive le bouton de soumission du formulaire 
		echo "ISBN est non valide";
	}
}
