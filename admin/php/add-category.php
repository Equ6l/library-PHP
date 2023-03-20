<?php
session_start();

include('../includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin'] == 0)) {
    // On le redirige vers la page de login
    header('location:../../php/adminlogin.php');
} else {
    // Sinon on peut continuer. Après soumission du formulaire de creation
    if (TRUE === isset($_POST['create'])) {

        // On recupere le nom et le statut de la categorie
        $category = $_POST['categoryName'];
        $status = $_POST['status'];

        $sql = "INSERT INTO tblcategory(CategoryName,Status) VALUES(:category,:status)";
        $stmt = $dbh->prepare($sql);

        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        // On execute la requete
        $stmt->execute();

        // On stocke dans $_SESSION le message correspondant au resultat de loperation
        $lastInsertId = $dbh->lastInsertId();

        if ($lastInsertId) {
            $_SESSION['msg'] = "Catégorie créée";
            header('location:manage-categories.php');
        } else {
            $_SESSION['error'] = "Une erreur s'est produite";
            header('location:manage-categories.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de categories</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!------MENU SECTION START-->
    <?php include('../includes/header.php'); ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4 class="header-line">Ajouter une catégorie</h4>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <div class="card">
                        <h6 class="card-header">
                            <strong>Informations de la catégorie</strong>
                        </h6>
                        <div class="card-body">
                            <form action="" method="POST">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="categoryName" id="floatingInputGroup1">
                                    <label for="floatingInputGroup1">Nom de la catégorie</label>
                                </div>
                                <div class="form-check mb-3">
                                    <label class="form-check-label">Statut :</label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" class="form-check-input" name="status" value="1" checked="checked"> Active
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="status" value="0"> Inactive
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" name="create" class="btn btn-primary">ADD CATEGORY</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- On affiche le titre de la page-->
    <!-- On affiche le formulaire de creation-->
    <!-- Par defaut, la categorie est active-->

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>