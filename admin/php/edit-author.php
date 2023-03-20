<?php
session_start();

include('../includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
     // On le redirige vers la page de login  
     header("location:../.././php/adminlogin.php");
} else {
     if (isset($_POST['update'])) {

          $author = $_POST['author'];
          $autid = intval($_GET['autid']);

          $sql = "UPDATE tblauthors SET AuthorName = :author WHERE id=:id";
          $stmt = $dbh->prepare($sql);
          $stmt->bindParam(':author', $author, PDO::PARAM_STR);
          $stmt->bindParam(':id', $autid, PDO::PARAM_STR);

          $stmt->execute();

          // On stocke dans $_SESSION le message "Auteur mis a jour"
          $_SESSION['updatemsg'] = "Auteur mis a jour";
          // On redirige l'utilisateur vers manage-authors.php
          header('location:manage-authors.php');
     }

     $autid = intval($_GET['autid']);
     $sql = "SELECT * FROM tblauthors WHERE id=:id";
     $stmt = $dbh->prepare($sql);
     $stmt->bindParam(':id', $autid);

     $stmt->execute();

     $result = $stmt->fetchAll(PDO::FETCH_OBJ);
}

?>
<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliothèque en ligne | Auteurs</title>
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
                         <h4 class='header-line'>Edition de l'auteur</h4>
                         </h4>
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
                                             <input type="text" class="form-control" name="author" placeholder="" id="floatingInputGroup1">
                                             <label for="floatingInputGroup1">Renommer l'auteur</label>
                                        </div>
                                        <div>

                                        </div>
                                        <button type="submit" name="update" class="btn btn-primary">UPDATE</button>
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