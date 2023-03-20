<?php
// On demarre ou on recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('../includes/config.php');

// On invalide le cache de session $_SESSION['alogin'] = ''
if (isset($_SESSION['login']) && $_SESSION['alogin'] != '') {
    $_SESSION['alogin'] = '';
}

// Apres la soumission du formulaire de login (plus bas dans ce fichier)
// On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialis�e $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas

if (isset($_POST['vercode'], $_SESSION['vercode'])) {
    $adminVercode = $_POST['vercode'];
    $sessionVercode = $_SESSION['vercode'];

    if ($adminVercode != $sessionVercode) {
        // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
        echo "<script>alert('Code de vérification incorrect')</script>";
    } else {
        // Le code est correct, on peut continuer
        // On recupere le nom de l'utilisateur saisi dans le formulaire
        $adminEmail = $_POST['adminEmail'];

        // On construit la requete qui permet de retrouver l'utilisateur a partir de son nom et de son mot de passe
        // depuis la table admin
        $sql = "SELECT * FROM admin WHERE AdminEmail = :adminEmail";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':adminEmail', $adminEmail);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);

        // Si le resultat de recherche n'est pas vide 
        if (!empty($result) && password_verify($_POST['adminPassword'], $result->Password)) {
            // On stocke le nom de l'utilisateur  $_POST['username'] en session $_SESSION
            $_SESSION['alogin'] = $_POST['adminEmail'];
            // echo "Vous êtes connecté !";
            // On redirige l'utilisateur vers le tableau de bord administration (n'existe pas encore)
            header("location:../admin/php/dashboard.php");
        } else {
            // sinon le login est refuse. On le signal par une popup
            echo "<script>alert('Accès refusé')</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html>

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
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('../includes/header.php'); ?>

    <!--On affiche le titre de la page-->
    <div class="container">
        <div class="row text-center m-3">
            <div class="col-12">
                <h3>LOGIN ADMIN</h3>
            </div>
        </div>
        <!--On affiche le formulaire de login-->
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                <form method="POST" action="adminlogin.php">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label class="form-label">Entrez votre pseudo</label>
                            <input type="text" class="form-control" name="adminName" required>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label class="form-label">Entrez votre email</label>
                            <input type="text" class="form-control" name="adminEmail" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                            <label class="form-label">Entrez votre mot de passe</label>
                            <input type="password" class="form-control" name="adminPassword" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                            <label>Code de vérification</label>
                            <input type="text" class="form-control" name="vercode" required>
                            <!--A la suite de la zone de saisie du captcha, on ins�re l'image cr��e par captcha.php : <img src="captcha.php">  -->
                            <img src="captcha.php">
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="alogin" class="btn btn-primary">LOGIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>