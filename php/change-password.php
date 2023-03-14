<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('../includes/config.php');

// Si l'utilisateur n'est pas logue, on le redirige vers la page de login (index.php)
if (strlen($_SESSION['login']) == 0) {
	header('index.php');
	// sinon, on peut continuer,
} else {
	// si le formulaire a ete envoye : $_POST['change'] existe
	if (TRUE === isset($_POST['change'])) {
		// On recupere le mot de passe et on le crypte (fonction php password_hash)
		$changePassword = password_hash($_POST['changePassword'], PASSWORD_DEFAULT);
		// On recupere l'email de l'utilisateur dans le tabeau $_SESSION
		$email = $_SESSION['login'];

		// On cherche en base l'utilisateur avec ce mot de passe et cet email
		$sql = "SELECT * FROM tblreaders WHERE EmailId = :emailId";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam('emailId', $email);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_OBJ);

		// Si le resultat de recherche n'est pas vide
		if (!empty($result) && password_verify($_POST['currentPassword'], $result->Password)) {
			// On met a jour en base le nouveau mot de passe (tblreader) pour ce lecteur
			$sql = "UPDATE tblreaders SET Password = :changePassword WHERE EmailId = :emailId";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':changePassword', $changePassword);
			$stmt->bindParam(':emailId', $email);
			$stmt->execute();

			// On stocke le message d'operation reussie
			$done = "Votre changement de mot de passe a bien été pris en compte";
			echo $done;
			// sinon (resultat de recherche vide)
		} else {
			// On stocke le message "mot de passe invalide"
			$failed = "Mot de passe actuel invalide";
			echo $failed;
		}
	}
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<title>Gestion de bibliotheque en ligne | changement de mot de passe</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- FONT AWESOME STYLE  -->
	<link href="../assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="../assets/css/style.css" rel="stylesheet" />

	<!-- Penser au code CSS de mise en forme des message de succes ou d'erreur -->

</head>
<script type="text/javascript">
	function valid() {
		let input = document.querySelectorAll("input");
		let span = document.getElementById('hey');
		if (input[1].value == input[2].value) {
			// TRUE si les mots de passe saisis dans le formulaire sont identiques
			console.log("true");
			span.innerHTML = `Mot de passe correct`;
			span.style.color = 'green';
			return true
		} else {
			// FALSE sinon
			for (let i = 1; i < 3; i++) {
				input[i].style.border = '1px solid red';
			}
			console.log("false");
			span.innerHTML = `Mot de passe incorrect`;
			span.style.color = 'red';
			return false
		}
	}
</script>

<body>
	<!-- Mettre ici le code CSS de mise en forme des message de succes ou d'erreur -->
	<?php include('../includes/header.php'); ?>
	<div class="container">
		<div class="row text-center m-3">
			<div class="col-12">
				<!--On affiche le titre de la page : CHANGER MON MOT DE PASSE-->
				<h3>CHANGER MON MOT DE PASSE</h3>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<form action="change-password.php" method="POST" onSubmit="return valid()">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
							<label for="currentPassword" class="form-label">Mot de passe actuel</label>
							<input type="password" name="currentPassword" class="form-control" required>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
							<label class="form-label">Nouveau mot de passe</label>
							<input type="password" class="form-control" required>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
							<label for="changePassword" class="form-label">Confirmez le nouveau mot de passe</label>
							<input type="password" name="changePassword" class="form-control" required>
							<span id="hey"></span>
						</div>
					</div>
					<div class="mb-3">
						<input type="submit" name="change" class="btn btn-outline-danger" id="state" value="UPDATE">
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include('../includes/footer.php'); ?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>