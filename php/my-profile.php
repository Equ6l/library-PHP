<?php
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('../includes/config.php');

// Si l'utilisateur n'est plus logué
// On le redirige vers la page de login
// Sinon on peut continuer. Après soumission du formulaire de profil
if (strlen($_SESSION['login']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoye vers la page de login : index.php
    header('location:index.php');
} else {

    if (TRUE === isset($_POST['update'])) {
        // On recupere l'identifiant du lecteur dans le tableau $_SESSION
        $readerId = $_SESSION['rdid'];

        // On prépare la requete pour récupérer en base de données les valeurs dans la table tblreaders
        $fullName = $_POST['fullName'];
        $mobileNumber = $_POST['mobileNumber'];
        $emailId = $_POST['emailId'];

        // On prépare la requete pour mettre à jour les valeurs dans la table tblreaders
        $sql = "UPDATE tblreaders SET FullName = :FullName, MobileNumber = :MobileNumber, EmailId = :EmailId WHERE ReaderId = :readerId";

        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":FullName", $fullName);
        $stmt->bindParam(":MobileNumber", $mobileNumber);
        $stmt->bindParam(":EmailId", $emailId);
        $stmt->bindParam(":readerId", $_SESSION['rdid']);

        $stmt->execute();

        $done = "Vos changements ont été pris en compte";
        echo $done;
    }
    $readerId = $_SESSION['rdid'];

    $sql = "SELECT * FROM tblreaders WHERE ReaderId = :readerId";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":readerId", $readerId);

    $stmt->execute();
    $readerData = $stmt->fetch(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Profil</title>
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

    <div class="container">
        <div class="row text-center m-3">
            <div class="col-12">
                <h3>MON COMPTE</h3>
            </div>
        </div>
        <!--On affiche le formulaire de modification d'informations-->
        <form action="my-profile.php" method="POST">
            <div class="row justify-content-center">
                <div class=" col-xs-12 col-sm-6 col-md-6 col-lg-8">
                    <div class="row"><!--On affiche l'identifiant - non editable-->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label class="form-label">Mon identifiant</label>
                            <input type="text" class="form-control" value="<?php echo $readerData->ReaderId ?>" disabled>
                        </div>

                        <!--On affiche la date d'enregistrement - non editable-->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label class="form-label">Date d'enregistrement</label>
                            <input type="text" class="form-control" value="<?php echo $readerData->RegDate ?>" disabled>
                        </div>
                    </div>

                    <div class="row"><!--On affiche la date de derniere mise a jour - non editable-->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label class="form-label">Dernière mise à jour</label>
                            <input type="text" class="form-control" value="<?php echo $readerData->UpdateDate ?>" disabled>
                        </div>


                        <!--On affiche la statut du lecteur - non editable-->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label class="form-label">Statut</label>
                            <input style="color:green" type="text" class="form-control" value="<?php
                                                                                                if ($readerData->Status) {
                                                                                                    echo "Actif";
                                                                                                }
                                                                                                ?>" disabled>
                        </div>
                    </div>

                    <div class="row"><!--On affiche le nom complet - editable-->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="fullName" class="form-label">Nom Complet</label>
                            <input type="text" name="fullName" value="<?php echo $readerData->FullName ?>" class="form-control">
                        </div>


                        <!--On affiche le numero de portable- editable-->
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="mobileNumber" class="form-label">Numéro de téléphone</label>
                            <input type="text" name="mobileNumber" value="<?php echo $readerData->MobileNumber ?>" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                            <label for="emailId" class="form-label">Email</label>
                            <input type="text" name="emailId" class="form-control" value="<?php echo $readerData->EmailId ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="update" class="btn btn-outline-success" value="UPDATE">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>