<?php
session_start();

include('../includes/config.php');

// Si l'utilisateur n'est plus logué
if (strlen($_SESSION['alogin']) == 0) {
    // On le redirige vers la page de login  
    header("location:../.././php/adminlogin.php");
} else {
    // Sinon
    // Apres soumission du formulaire de categorie
    if (isset($_POST['update'])) {

        // On recupere l'identifiant, le statut, le nom
        $category = $_POST['category'];
        $catid = intval($_GET['catid']);

        // On prepare la requete de mise a jour
        // On prepare la requete de recherche des elements de la categorie dans tblcategory

        $sql = "UPDATE tblcategory SET CategoryName=:category WHERE id=:catid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':catid', $catid, PDO::PARAM_STR);

        // On execute la requete
        $stmt->execute();

        // On stocke dans $_SESSION le message "Categorie mise a jour"
        $_SESSION['updatemsg'] = "Catégorie mise a jour";
        // On redirige l'utilisateur vers manage-categories.php
        header('location:manage-categories.php');
    }

    $catid = intval($_GET['catid']);
    $sql = "SELECT * FROM tblcategory WHERE id=:catid";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':catid', $catid);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
    <!DOCTYPE html>
    <html lang="FR">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <title>Gestion de bibliothèque en ligne | Categories</title>
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
        <!-- On affiche le titre de la page "Editer la categorie-->
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4 class='header-line'>Edition de la catégorie</h4>
                        </h4>
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
                                        <input type="text" class="form-control" name="category" placeholder="" id="floatingInputGroup1">
                                        <label for="floatingInputGroup1">Renommer la catégorie</label>
                                    </div>
                                    <div>

                                    </div>
                                    <!-- Si la categorie est active (status == 1)-->
                                    <!-- On coche le bouton radio "actif"-->
                                    <!-- Sinon-->
                                    <!-- On coche le bouton radio "inactif"-->
                                    <button type="submit" name="update" class="btn btn-primary">UPDATE</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('../../includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>

    </html>
<?php } ?>