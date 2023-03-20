<?php
session_start();

include('../includes/config.php');

if (strlen($_SESSION['alogin'] == 0)) {
      // On le redirige vers la page de login
      header('location:../../php/adminlogin.php');
} else {
      // Sinon on peut continuer. Après soumission du formulaire de creation
      if (TRUE === isset($_POST['add'])) {

            // On recupere le nom et le statut de la categorie
            $author = $_POST['authorName'];

            $sql = "INSERT INTO tblauthors(AuthorName) VALUES(:author)";
            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(':author', $author, PDO::PARAM_STR);

            // On execute la requete
            $stmt->execute();

            // On stocke dans $_SESSION le message correspondant au resultat de loperation
            $lastInsertId = $dbh->lastInsertId();

            if ($lastInsertId) {
                  $_SESSION['msg'] = "Auteur créé";
                  header('location:manage-authors.php');
            } else {
                  $_SESSION['error'] = "Une erreur s'est produite";
                  header('location:manage-authors.php');
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
      <?php include('../includes/header.php'); ?>
      <div class="content-wrapper">
            <div class="container">
                  <div class="row">
                        <div class="col-12">
                              <h4 class="header-line">Ajouter un auteur</h4>
                        </div>
                  </div>
                  <div class="row justify-content-center">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                              <div class="card">
                                    <h6 class="card-header">
                                          <strong>Informations de l'auteur</strong>
                                    </h6>
                                    <div class="card-body">
                                          <form action="" method="POST">
                                                <div class="form-floating mb-3">
                                                      <input type="text" class="form-control" name="authorName" placeholder="authorName" id="floatingInputGroup1">
                                                      <label for="floatingInputGroup1">Nom de l'auteur</label>
                                                </div>
                                                <button type="submit" name="add" class="btn btn-primary">ADD AUTHOR</button>
                                          </form>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
      <!-- CONTENT-WRAPPER SECTION END-->
      <?php include('../includes/footer.php'); ?>
      <!-- FOOTER SECTION END-->
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>