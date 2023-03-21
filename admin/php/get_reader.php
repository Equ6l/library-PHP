<?php
/* Cette fonction est declenchee au moyen d'un appel AJAX depuis le formulaire de sortie de livre */
/* On recupere le numero l'identifiant du lecteur SID---*/

$SID = $_SESSION['rdid'];

// On prepare la requete de recherche du lecteur correspondant
$sql = "SELECT * FROM tblreaders WHERE ReaderId = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':id', $SID);

// On execute la requete
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_OBJ);

// Si un resultat est trouve
if ($result) {
	// On affiche le nom du lecteur
	echo $result->FullName;
	// On active le bouton de soumission du formulaire
	echo "<script> document.getElementById('submitButton').disabled = false</script>";
} else if ($result->ReaderId === null) {
	// Sinon
	// Si le lecteur n existe pas
	// On affiche que "Le lecteur est non valide"
	echo "Le lecteur est non valide";
	// On desactive le bouton de soumission du formulaire
	echo "<script>document.getElementById('submitButton').disabled = true</script>";
} else {
	// Si le lecteur est bloque
	// On affiche lecteur bloque
	echo $result->ReaderId;
	// On desactive le bouton de soumission du formulaire
	echo "<script>document.getElementById('submitButton').disabled = true</script>";
}
