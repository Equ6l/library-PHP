<?php
session_start();

include('../includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    if (isset($_POST['validate'])) {

        if (!empty($_POST['rdid'] && $_POST['isbn'])) {

            $SID = strtoupper($_POST['rdid']);
            $ISBN = $_POST['isbn'];

            // $sql2 = "SELECT id FROM tblbooks WHERE";

            $sql = "INSERT INTO tblissuedbookdetails(ReaderID, BookId, ReturnStatus) 
            SELECT tblreaders.ReaderId, tblbooks.id, 0 
            FROM tblbooks 
            CROSS JOIN tblreaders 
            WHERE ReaderID=:reader 
            AND tblbooks.ISBNNumber=:book";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':reader', $SID);
            $stmt->bindParam(':book', $ISBN);
            $stmt->execute();

            $lastInsertId = $dbh->lastInsertId();

            if ($lastInsertId) {
                $_SESSION['msg'] = "L'emprunt a bien été enregistrée.";
                header('location:manage-issued-books.php');
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:manage-issued-books.php');
            }
        } else {
            echo "Veuillez rentrer des données";
        }
    }

?>

    <!DOCTYPE html>
    <html lang="FR">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <title>Gestion de bibliotheque en ligne | Ajout de sortie</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FONT AWESOME STYLE  -->
        <link href="../assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="../assets/css/style.css" rel="stylesheet" />
        <script>
            // On crée une fonction JS pour récuperer le nom du lecteur à partir de son identifiant
            function getReader(reader) {
                const inputSID = document.getElementById("sid");
                const url = './get_reader.php?rdid=' + reader;
                fetch(url)
                    .then((response) => response.text())
                    .then((data) => {
                        inputSID.innerHTML = data;
                    })
            }

            // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN
            function getBook(book) {
                const inputISBN = document.getElementById("isbn");
                const url = './get_book.php?bookid=' + book;
                fetch(url)
                    .then((response) => response.text())
                    .then((data) => {
                        inputISBN.innerHTML = data;
                    })
            }
        </script>
    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include('../includes/header.php'); ?>
        <!-- MENU SECTION END-->

        <!-- Dans le formulaire du sortie, on appelle les fonctions JS de recuperation du nom du lecteur et du titre du livre 
 sur evenement onBlur-->

        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4 class="header-line">Livres empruntés</h4>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <div class="card">
                            <h6 class="card-header">
                                <strong>Informations du lecteur</strong>
                            </h6>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="rdid" placeholder="reader" onBlur="getReader(this.value);">
                                        <label for="rdid">Identifiant lecteur</label>
                                        <span id="sid"></span>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="isbn" placeholder="ISBN" onBlur="getBook(this.value);">
                                        <label for="isbn">ISBN</label>
                                        <span id="isbn"></span>
                                    </div>
                                    <button type="submit" name="validate" id="searchButton" class="btn btn-primary">VALIDATE</button>
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
<?php } ?>