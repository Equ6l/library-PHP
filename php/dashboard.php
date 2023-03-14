<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de données
include('../includes/config.php');


if (strlen($_SESSION['login']) == 0) {
     // Si l'utilisateur est déconnecté
     // L'utilisateur est renvoye vers la page de login : index.php
     header('location:index.php');
} else {
     // On recupere l'identifiant du lecteur dans le tableau $_SESSION
     $readerId = $_SESSION['rdid'];

     // On veut savoir combien de livres ce lecteur a emprunte
     // On construit la requete permettant de le savoir a partir de la table tblissuedbookdetails
     $sql = "SELECT COUNT(*) AS ReaderId FROM tblissuedbookdetails WHERE readerId = :ReaderId";
     $stmt = $dbh->prepare($sql);
     $stmt->bindParam(":ReaderId", $readerId);
     $stmt->execute();

     // On stocke le resultat dans une variable
     $bookBorrowed = $stmt->fetch(PDO::FETCH_OBJ);

     // On veut savoir combien de livres ce lecteur n'a pas rendu
     // On construit la requete qui permet de compter combien de livres sont associes a ce lecteur avec le ReturnStatus = 0
     $status = 0;
     $sql = "SELECT COUNT(*) AS ReturnStatus FROM tblissuedbookdetails WHERE readerId = :Readerid AND ReturnStatus = :status";
     $stmt = $dbh->prepare($sql);
     $stmt->bindParam(":Readerid", $readerId);
     $stmt->bindParam(":status", $status);
     $stmt->execute();

     // On stocke le resultat dans une variable
     $bookNotReturned = $stmt->fetch(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
     <title>Gestion de librairie en ligne | Tableau de bord utilisateur</title>
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

     <div class="container">
          <div class="row text-center mt-3">
               <div class="col-12">
                    <h3>TABLEAU DE BORD</h3>
               </div>
          </div>
          <div class="row">
               <div class="card my-3 p-0" style="width: 18rem;"><!-- On affiche la carte des livres empruntés par le lecteur-->
                    <img src="../img/book.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5>Livres empruntés</h5>
                         <?php
                         echo "Vous avez emprunté " . $bookBorrowed->ReaderId . " livre(s).";
                         ?>
                         <a href="issued-books.php" class="btn btn-outline-primary mt-2">Voir plus</a>
                    </div>
               </div>
               <div class="card m-3 p-0" style="width: 18rem;"><!-- On affiche la carte des livres non rendus le lecteur-->
                    <img src="../img/books2.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                         <h5>Livres rendus</h5>
                         <?php
                         echo "Vous devez retourner " . $bookNotReturned->ReturnStatus . " livre(s).";
                         ?>
                         <a href="#" class="btn btn-outline-primary mt-2">Voir plus</a>
                    </div>
               </div>
          </div>
     </div>

     <?php include('../includes/footer.php'); ?>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
<?php  ?>