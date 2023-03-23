<?php
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero l'identifiant du lecteur SID---*/
require_once("../includes/config.php");

if (!empty($_GET['rdid'])) {

	$SID = strtoupper($_GET['rdid']);

	// On prepare la requete de recherche du lecteur correspondant
	$sql = "SELECT ReaderId, FullName, Status FROM tblreaders WHERE ReaderId = :id";
	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':id', $SID);

	// On execute la requete
	$stmt->execute();

	$result = $stmt->fetch(PDO::FETCH_OBJ);

	// Si un resultat est trouve
	if (!empty($result)) {
		if ($result->Status === 0) {
			echo "L'utilisateur " . $result->ReaderId . " est bloqué";
			echo "<script>document.getElementById('searchButton').disabled = true</script>";
		} else {
			// On affiche le nom du lecteur
			echo htmlentities("Lecteur : " . $result->FullName);
			// On active le bouton de soumission du formulaire
			echo "<script> document.getElementById('searchButton').disabled = false</script>";
		}
	} else {
		// Sinon
		// Si le lecteur n existe pas
		// On affiche que "Le lecteur est non valide"
		echo "Le lecteur est non valide";
		// On desactive le bouton de soumission du formulaire
		echo "<script>document.getElementById('searchButton').disabled = true</script>";
	}
}
