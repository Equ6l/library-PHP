<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('../includes/config.php');


// Après la soumission du formulaire de compte (plus bas dans ce fichier)
// On vérifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
// $_POST["vercode"] et la valeur initialisée $_SESSION["vercode"] lors de l'appel à captcha.php (voir plus bas)
if (isset($_POST['vercode'], $_SESSION['vercode'])) {
    $userVercode = $_POST['vercode'];
    $sessionVercode = $_SESSION['vercode'];

    if ($userVercode != $sessionVercode) {
        // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
        echo "<script>alert('Code de vérification incorrect')</script>";
    } else {
        //On lit le contenu du fichier readerid.txt au moyen de la fonction 'file'. Ce fichier contient le dernier identifiant lecteur cree.
        $readerid = file("readerid.txt");

        // On incrémente de 1 la valeur lue
        $readerid[0]++;

        file_put_contents("readerid.txt", $readerid);

        // On récupère le nom saisi par le lecteur
        $fullname = $_POST["fullname"];

        // On récupère le numéro de portable
        $mobilenumber = $_POST["mobilenumber"];

        // On récupère l'email
        $emailid = $_POST["emailid"];

        // On récupère le mot de passe
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // On fixe le statut du lecteur à 1 par défaut (actif)
        $status = 1;

        // On prépare la requete d'insertion en base de données de toutes ces valeurs dans la table tblreaders
        $sql = "INSERT INTO tblreaders (ReaderId, FullName, EmailId, MobileNumber, Password, Status) VALUES (:readerid, :fullname, :emailid, :mobilenumber, :password, :status)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":readerid", $readerid[0]);
        $stmt->bindParam(":fullname", $fullname);
        $stmt->bindParam(":emailid", $emailid);
        $stmt->bindParam(":mobilenumber", $mobilenumber);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":status", $status);

        // On éxecute la requete
        $stmt->execute();

        // On récupère le dernier id inséré en bd (fonction lastInsertId)
        $lastId = $dbh->lastInsertId();

        // Si ce dernier id existe, on affiche dans une pop-up que l'opération s'est bien déroulée, et on affiche l'identifiant lecteur (valeur de $hit[0] ($readerid[0]))
        if ($lastId) {
            echo "<script>alert('Enregistrement terminé ! Votre identifiant est le'.$readerid[0].')</script>";
        } else {
            // Sinon on affiche qu'il y a eu un problème
            echo "<script>alert('Une erreur est survenue, veuillez réessayer.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>Gestion de bibliotheque en ligne | Signup</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <!-- link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' / -->
    <script type="text/javascript">
        // On cree une fonction valid() sans paramètre qui renvoie 
        function valid() {
            let input = document.querySelectorAll("input");
            if (input[3].value == input[4].value) {
                // TRUE si les mots de passe saisis dans le formulaire sont identiques
                console.log("coucou")
                return true
            } else {
                // FALSE sinon
                console.log("2")
                return false
            }
        }
        // On cree une fonction avec l'email passé en paramêtre et qui vérifie la disponibilité de l'email
        // Cette fonction effectue un appel AJAX vers check_availability.php
        function checkAvailability(email) {
            const dispo = document.querySelector('#test');
            const url = './check_availability.php?email=' + email;
            fetch(url)
                .then((response) => response.json())
                .then((data) => {
                    if (data == 'disponible') {
                        console.log("coucou je suis dispo")
                        dispo.textContent = data;
                        document.getElementById('state').disabled = false;
                    } else if (data == 'indisponible') {
                        dispo.textContent = data;
                        console.log("coucou je suis pas dispo")
                        dispo.textContent = "adresse mail indisponible";
                        document.getElementById('state').disabled = true;
                    } else if (data == 'invalide') {
                        dispo.textContent = data;
                        document.getElementById('state').disabled = true;
                    }
                })
        }
    </script>
</head>

<body>
    <!-- On inclue le fichier header.php qui contient le menu de navigation-->
    <?php include('../includes/header.php'); ?>
    <div class="container">
        <div class="row text-center m-3">
            <div class="col-12">
                <!--On affiche le titre de la page : CREER UN COMPTE-->
                <h3>CRÉER UN COMPTE</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8">
                <!--On affiche le formulaire de creation de compte-->
                <form action="signup.php" method="POST" onSubmit="return valid()">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                            <label for="fullname" class="form-label">Entrez votre nom complet</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="emailid" class="form-label">Entrez votre email</label>
                            <input type="text" name="emailid" class="form-control" id="emailid" onBlur="checkAvailability(this.value);" required>
                            <span id="test"></span>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="mobilenumber" class="form-label">Entrez votre numéro de téléphone</label>
                            <input type="text" name="mobilenumber" class="form-control" id="mobilenumber" required>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="password" class="form-label">Entrez votre mot de passe</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="confirmation" class="form-label">Vérifiez votre mot de passe</label>
                            <input type="password" name="confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-2">
                            <label class="form-label">Code de vérification</label>
                            <input type="text" name="vercode" class="form-control" required>
                            <!-- A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
                            <img src="captcha.php">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="login" class="btn btn-success" id="state" value="CREATE">
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>