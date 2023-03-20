<?php
// On démarre (ou on récupère) la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('../includes/config.php');

// Si l'utilisateur est déconnecté
if (strlen($_SESSION['alogin']) == 0) {
  // L'utilisateur est renvoyé vers la page de login : index.php
  header("location:../.././php/adminlogin.php");
} else {
  // sinon on récupère les informations à afficher depuis la base de données
  // On récupère le nombre de livres depuis la table tblbooks
  $sql = "SELECT COUNT(id) FROM tblbooks";
  $query = $dbh->prepare($sql);
  $query->execute();
  $results = $query->fetch(PDO::FETCH_COLUMN);

  // On récupère le nombre de livres en prêt depuis la table tblissuedbookdetails
  $sql1 = "SELECT COUNT(id) FROM tblissuedbookdetails";
  $query1 = $dbh->prepare($sql1);
  $query1->execute();
  $results1 = $query1->fetch(PDO::FETCH_COLUMN);

  // On récupère le nombre de livres retournés  depuis la table tblissuedbookdetails
  // Ce sont les livres dont le statut est à 1
  $status = 1;
  $sql2 = "SELECT COUNT(id) from tblissuedbookdetails where ReturnStatus=:status";
  $query2 = $dbh->prepare($sql2);
  $query2->bindParam(':status', $status, PDO::PARAM_STR);
  $query2->execute();
  $results2 = $query2->fetch(PDO::FETCH_COLUMN);

  // On récupère le nombre de lecteurs dans la table tblreaders
  $sql3 = "SELECT COUNT(id) FROM tblreaders";
  $query3 = $dbh->prepare($sql3);
  $query3->execute();
  $results3 = $query3->fetch(PDO::FETCH_COLUMN);

  // On récupère le nombre d'auteurs dans la table tblauthors
  $sql4 = "SELECT COUNT(id) FROM tblauthors";
  $query4 = $dbh->prepare($sql4);
  $query4->execute();
  $results4 = $query4->fetch(PDO::FETCH_COLUMN);

  // On récupère le nombre de catégories dans la table tblcategory
  $sql5 = "SELECT COUNT(id) FROM tblcategory";
  $query5 = $dbh->prepare($sql5);
  $query5->execute();
  $results5 = $query5->fetch(PDO::FETCH_COLUMN);
?>
  <!DOCTYPE html>
  <html lang="FR">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Gestion de bibliothèque en ligne | Tab bord administration</title>
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
    <!-- On affiche le titre de la page : TABLEAU DE BORD ADMINISTRATION-->
    <div class="container">
      <div class="row text-center mt-5">
        <div class="col-12">
          <h3>TABLEAU DE BORD ADMINISTRATION</h3>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8">
          <div class="row my-5 text-center">
            <!-- On affiche la carte Nombre de livres -->
            <div class="col-lg-4">
              <div class="card" style="width: 15rem;">
                <span class="fa fa-book fa-5x" style="color:#72DF61;"></span>
                <div class="card-body">
                  <h5>Nombre de livres</h5>
                  <?= "<strong>" . $results . "</strong>"; ?>
                </div>
              </div>
            </div>

            <!-- On affiche la carte Nombre de livres -->
            <div class="col-lg-4">
              <div class="card" style="width: 15rem;">
                <span class="fa fa-bars fa-5x" style="color:#FF764F;"></span>
                <div class="card-body">
                  <h5>Livres en prêt</h5>
                  <?= "<strong>" . $results1 . "</strong>"; ?>
                </div>
              </div>
            </div>

            <!-- On affiche la carte Livres retournés -->
            <div class="col-lg-4">
              <div class="card" style="width: 15rem;">
                <span class="fa fa-recycle fa-5x" style="color:#3D9E5D;"></span>
                <div class="card-body">
                  <h5>Livres retournés</h5>
                  <?= "<strong>" . $results2 . "</strong>"; ?>
                </div>
              </div>
            </div>


          </div>
          <div class="row mb-5 text-center">
            <!-- On affiche la carte Lecteurs -->
            <div class="col-lg-4">
              <div class="card" style="width: 15rem;">
                <span class="fa fa-users fa-5x" style="color:#6480E7;"></span>
                <div class="card-body">
                  <h5>Lecteurs</h5>
                  <?= "<strong>" . $results3 . "</strong>"; ?>
                </div>
              </div>
            </div>
            <!-- On affiche la carte Auteurs -->
            <div class="col-lg-4">
              <div class="card" style="width: 15rem;">
                <span class="fa fa-users fa-5x" style="color:#FF5A59;"></span>
                <div class="card-body">
                  <h5>Auteurs</h5>
                  <?= "<strong>" . $results4 . "</strong>"; ?>
                </div>
              </div>
            </div>

            <!-- On affiche la carte Catégories -->
            <div class="col-lg-4">
              <div class="card" style="width: 15rem;">
                <span class="fa fa-file fa-5x" style="color:#FFC159;"></span>
                <div class="card-body">
                  <h5>Catégories</h5>
                  <?= "<strong>" . $results5 . "</strong>"; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  </body>

  </html>
<?php } ?>