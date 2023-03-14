<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('../includes/config.php');

error_log(print_r($_SESSION, 1));

// On invalide le cache de session
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
	$_SESSION['login'] = '';
}

if (TRUE === isset($_POST['login'])) {
	// Apr�s la soumission du formulaire de login ($_POST['login'] existe - voir pourquoi plus bas)
	// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
	// $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
	if ($_POST['vercode'] != $_SESSION['vercode']) {
		// Le code est incorrect on informe l'utilisateur par une fenetre pop_up
		echo "<script>alert('Code de vérification incorrect')</script>";
	} else {
		// Le code est correct, on peut continuer
		// On recupere le mail de l'utilisateur saisi dans le formulaire
		$mail = $_POST['emailid'];
		// On construit la requete SQL pour recuperer l'id, le readerId et l'email du lecteur � partir des deux variables ci-dessus
		// dans la table tblreaders
		$sql = "SELECT EmailId, Password, ReaderId, Status FROM tblreaders  WHERE EmailId = :email";
		$query = $dbh->prepare($sql);
		$query->bindParam(':email', $mail, PDO::PARAM_STR);
		// On execute la requete
		$query->execute();
		// On stocke le resultat de recherche dans une variable $result
		$result = $query->fetch(PDO::FETCH_OBJ);

		if (!empty($result) && password_verify($_POST['password'], $result->Password)) {
			// Si le resultat de recherche n'est pas vide
			// On stocke l'identifiant du lecteur (ReaderId) dans $_SESSION['rdid']
			$_SESSION['rdid'] = $result->ReaderId;

			if ($result->Status == 1) {
				// Si le statut du lecteur est actif (egal a 1)
				// On stocke l'email du lecteur dans $_SESSION['login']
				$_SESSION['login'] = $_POST['emailid'];
				// l'utilisateur est redirige vers dashboard.php
				header('location:dashboard.php');
			} else {
				// Sinon le compte du lecteur a ete bloque. On informe l'utilisateur par un popu
				echo "<script>alert('Votre compte à été bloqué')</script>";
			}
		} else {
			echo "<script>alert('Utilisateur inconnu')</script>";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="FR">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Gestion de bibliotheque en ligne</title>
	<!-- BOOTSTRAP CORE STYLE  -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- FONT AWESOME STYLE  -->
	<link href="../assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLE  -->
	<link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
	<!--On inclue ici le menu de navigation includes/header.php-->
	<?php include('../includes/header.php'); ?>

	<!-- On insere le titre de la page (LOGIN UTILISATEUR) -->
	<div class="container">
		<div class="row text-center">
			<div class="col-12 my-3">
				<h3>LOGIN LECTEUR</h3>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4">
				<form method="post" action="index.php">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
							<label class="form-label">Entrez votre email</label>
							<input type="text" class="form-control" name="emailid" required>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
							<label class="form-label">Entrez votre mot de passe</label>
							<input type="password" class="form-control" name="password" required>
							<a href="user-forgot-password.php">Mot de passe oublié ?</a>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
							<label>Code de vérification</label>
							<input type="text" class="form-control" name="vercode" required>
							<img src="captcha.php">
						</div>
					</div>
					<div class="mb-3">
						<button type="submit" name="login" class="btn btn-primary">LOGIN</button>
						<a href="signup.php">Je n'ai pas de compte</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php include('../includes/footer.php'); ?>
	<!-- FOOTER SECTION END-->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>