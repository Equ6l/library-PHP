<?php
session_start();

include('../includes/config.php');

// Si l'utilisateur n'est plus logué
// On le redirige vers la page de login
if (strlen($_SESSION['alogin']) === 0) {
	header("location:../.././php/adminlogin.php");
} else {
	if (isset($_POST['change'])) {
		// Sinon on peut continuer. Après soumission du formulaire de modification du mot de passe
		// Si le formulaire a bien ete soumis

		// On recupere le nouveau mot de passe
		$confirmPassword = password_hash($_POST['confirmPassword'], PASSWORD_DEFAULT);
		var_dump($confirmPassword);
		// On recupere le nom de l'utilisateur stocké dans $_SESSION
		$emailId = $_SESSION['alogin'];

		// On prepare la requete de recherche pour recuperer l'id de l'administrateur (table admin)
		// dont on connait le nom et le mot de passe actuel
		// On execute la requete
		$sql = "SELECT * FROM admin WHERE AdminEmail=:email";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':email', $emailId, PDO::PARAM_STR);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_OBJ);
		var_dump($result);

		// Si on trouve un resultat
		if (!empty($result) && password_verify($_POST['currentPassword'], $result->Password)) {
			// On prepare la requete de mise a jour du nouveau mot de passe de cet id
			$sql = "UPDATE admin SET Password = :confirmPassword WHERE id=:id";
			$stmt = $dbh->prepare($sql);
			$stmt->bindParam(':confirmPassword', $confirmPassword);
			$stmt->bindParam(':id', $result->id);
			$stmt->execute();
			// On execute la requete

			// On stocke un message de succès de l'operation
			// On purge le message d'erreur
			$msg = "Votre mot de passe a bien été modifié";
			$error = "";
		} else {
			// Sinon on a trouve personne	
			// On stocke un message d'erreur
			$error = "Mot de passe incorrect";
			$msg = "";
		}
	} else {
		// Sinon le formulaire n'a pas encore ete soumis
		// On initialise le message de succes et le message d'erreur (chaines vides)
		$error = "";
		$msg = "";
	}
?>

	<!DOCTYPE html>
	<html lang="FR">

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<title>Gestion bibliotheque en ligne</title>
		<!-- BOOTSTRAP CORE STYLE  -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
		<!-- FONT AWESOME STYLE  -->
		<link href="../assets/css/font-awesome.css" rel="stylesheet" />
		<!-- CUSTOM STYLE  -->
		<link href="../assets/css/style.css" rel="stylesheet" />
		<!-- Penser a mettre dans la feuille de style les classes pour afficher le message de succes ou d'erreur  -->
	</head>
	<script type="text/javascript">
		function valid() {
			let input = document.querySelectorAll("input");
			if (input[1].value == input[2].value) {
				// TRUE si les mots de passe saisis dans le formulaire sont identiques
				return true
			} else {
				// FALSE sinon
				alert("Les mots de passe sont différents.");
				return false
			}
		}
	</script>

	<body>
		<!------MENU SECTION START-->
		<?php include('../includes/header.php'); ?>
		<!-- MENU SECTION END-->

		<div class="content-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h4 class="header-line">Changer le mot de passe</h4>
					</div>
				</div>
				<?php if ($error) { ?>
					<div class="errorWrap"><strong>ERREUR</strong> : <?php echo htmlentities($error); ?>
					</div>
				<?php } else if ($msg) { ?>
					<div class="succWrap"><strong>SUCCES</strong>:<?php echo htmlentities($msg); ?>
					</div>
				<?php } ?>
				<div class="row justify-content-center">
					<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						<div class="card">
							<h6 class="card-header">
								<strong>Changer le mot de passe</strong>
							</h6>
							<div class="card-body">
								<form action="" method="POST" onsubmit="return valid();">
									<div class="mb-3">
										<label class="form-label">Mot de passe actuel : </label>
										<input type="password" class="form-control" name="currentPassword">
									</div>
									<div class="mb-3">
										<label class="form-label">Nouveau mot de passe :</label>
										<input type="password" class="form-control" name="newPassword">
									</div>
									<div class="mb-3">
										<label class="form-label">Confirmer le mot de passe :</label>
										<input type="password" class="form-control" name="confirmPassword">
									</div>
									<button type="submit" name="change" class="btn btn-primary">UPDATE</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- On affiche le formulaire de changement de mot de passe-->
		<!-- La fonction JS valid() est appelee lors de la soumission du formulaire onSubmit="return valid();" -->

		<!-- CONTENT-WRAPPER SECTION END-->
		<?php include('../includes/footer.php'); ?>
		<!-- FOOTER SECTION END-->
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
	</body>

	</html>
<?php } ?>