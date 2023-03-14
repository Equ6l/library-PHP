<?php
// On r�cup�re la session courante
session_start();

// On inclue le fichier de configuration et de connexion � la base de donn�es
include('../includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    // Si l'utilisateur est déconnecté
    // L'utilisateur est renvoye vers la page de login : index.php
    header('location:index.php');
} else { // Sinon on peut continuer
    // On récupère l'identifiant du lecteur dans le tableau $_SESSION
    $readerId = $_SESSION['rdid'];

    // On veut savoir quels livres ce lecteur a emprunté
    // On construit la requête permettant de le savoir à partir des tables tblissuedbookdetails et tblbooks
    $sql = "SELECT * FROM tblissuedbookdetails 
    JOIN tblbooks ON tblissuedbookdetails.BookId = tblbooks.Id
    WHERE readerId = :ReaderId";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":ReaderId", $readerId);
    $stmt->execute();

    // On stocke le resultat dans une variable
    $booksBorrowed = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliotheque en ligne | Gestion des livres</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FONT AWESOME STYLE  -->
    <link href="../assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="../assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <!--On insere ici le menu de navigation T-->
    <?php include('../includes/header.php'); ?>

    <div class="container">
        <div class="row text-center m-3">
            <div class="col-12">

                <h3>LIVRES EMPRUNTÉS</h3>
            </div>
        </div>
        <div class="row">
            <!-- On affiche CHAQUE livre emprunté par le lecteur avec numero, titre, ISBN, date de sortie et date de retour-->
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>ISBN</th>
                        <th>Date d'emprunt</th>
                        <th>Date de retour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 0;
                    foreach ($booksBorrowed as $book) {
                        $counter++ ?>
                        <tr>
                            <td> <?= $counter ?></td>
                            <td> <?= $book['BookName'] ?></td>
                            <td> <?= $book['BookId'] ?></td>
                            <td> <?= $book['IssuesDate'] ?></td>
                            <td> <?php
                                    if ($book['ReturnStatus'] == 0) {
                                        echo "Non retourné";
                                    } else {
                                        echo $book['ReturnDate'];
                                    }
                                    ?></td>
                        </tr>
                    <?php  }
                    ?>
                </tbody>
            </table>

        </div>


    </div>
    <!-- Si il n'y a pas de date de retour, on affiche non retourne -->
    <?php include('../includes/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>