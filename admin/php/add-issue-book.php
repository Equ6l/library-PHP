<?php
session_start();

include('../includes/config.php');
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
        // function getStudent(studentid) {
        //     let xhr = new XMLHttpRequest();
        //     xhr.open("GET", "get_student.php?studentid=" + studentid);
        //     xhr.responseType = "text";
        //     xhr.send();

        //     xhr.onload = function() {
        //         document.getElementById("get_student_name").innerHTML = xhr.response;
        //     }
        // }
        // On crée une fonction JS pour recuperer le titre du livre a partir de son identifiant ISBN

        // function getBook(bookid) {
        //     let xhr = new XMLHttpRequest();
        //     xhr.open("GET", "get_book.php?bookid=" + bookid);
        //     xhr.responseType = "text";
        //     xhr.send();

        //     xhr.onload = function() {
        //         document.getElementById("get_book_name").innerHTML = xhr.response;
        //     }
        // }
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
                                    <input type="text" class="form-control" name="reader" placeholder="reader" id="floatingInputGroup1">
                                    <label for="floatingInputGroup1">Identifiant lecteur</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="isbn" placeholder="ISBN" id="floatingInputGroup1">
                                    <label for="floatingInputGroup1">ISBN</label>
                                </div>
                                <button type="submit" name="add" id="submitButton" class="btn btn-primary">SEARCH</button>
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