<?php
session_start();

include('../includes/config.php');

if (strlen($_SESSION['alogin'] == 0)) {
  // On le redirige vers la page de login
  header('location:../../php/adminlogin.php');
} else {
  // On affiche notre liste de catégories et d'auteurs
  $sql1 = "SELECT * FROM tblcategory WHERE Status = 1 ORDER BY CategoryName";
  $result1 = $dbh->query($sql1);
  $sql2 = "SELECT * FROM tblauthors ORDER BY AuthorName";
  $result2 = $dbh->query($sql2);

  // Après la soumission du formulaire
  if (isset($_POST['add'])) {

    if (isset($_POST['author'])) {
      $author = $_POST['author'];
      $sql3 = "INSERT INTO tblauthors(AuthorName) VALUES(:authorName)";
      $stmt = $dbh->prepare($sql3);
      $stmt->bindParam('authorName', $author);
      $stmt->execute();

      $author = $dbh->lastInsertId();
    }

    $param = [
      'book' => $_POST['bookName'],
      'category' => $_POST['category'],
      'author' => $author,
      'isbn' => $_POST['isbn'],
      'price' => $_POST['price']
    ];

    // On recupere un tableau de paramètres
    var_dump($param);

    // On insere les valeurs dans la table books
    $sql = "INSERT INTO tblbooks(BookName, CatId, AuthorId, ISBNNumber, BookPrice) VALUES(:book, :category, :author, :isbn, :price)";
    $stmt = $dbh->prepare($sql);

    foreach ($param as $key => &$values) {
      $stmt->bindParam(':' . $key, $values);
    }

    // On execute la requete
    $stmt->execute();

    $lastInsertId = $dbh->lastInsertId();
    
    // On stocke dans $_SESSION le message correspondant au resultat de loperation
    if ($lastInsertId) {
      $_SESSION['msg'] = "Auteur créé";
      header('location:manage-books.php');
    } else {
      $_SESSION['error'] = "Une erreur s'est produite";
      header('location:manage-books.php');
    }
  }
?>

  <!DOCTYPE html>
  <html lang="FR">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Ajout de livres</title>
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
            <h4 class="header-line">Ajouter un livre</h4>
          </div>
        </div>
        <div class="row justify-content-center">
          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <div class="card">
              <h6 class="card-header">
                <strong>Informations du livre</strong>
              </h6>
              <div class="card-body">
                <form action="" method="POST">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="bookName" placeholder="Nom" id="floatingInputGroup1">
                    <label for="floatingInputGroup1">Nom du livre</label>
                  </div>
                  <select class="form-select form-select-md mb-3" aria-label="Default select example" name="category">
                    <option selected>Catégorie</option>
                    <?php while ($donnees = $result1->fetch(PDO::FETCH_ASSOC)) { ?>
                      <option value="<?= $donnees['id']; ?>"><?= $donnees['CategoryName'];
                                                            } ?></option>
                  </select>
                  <select class="form-select form-select-md mb-3" aria-label="Default select example" name="author" id="authors">
                    <option value="">Auteur</option>
                    <?php while ($donnees = $result2->fetch(PDO::FETCH_ASSOC)) { ?>
                      <option value="<?= $donnees['id']; ?>"><?= $donnees['AuthorName'];
                                                            }
                                                          } ?></option>
                  </select>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" placeholder="Auteur" name="author" id="addAuthor">
                    <label>Ajouter un auteur</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="isbn" placeholder="ISBN" id="floatingInputGroup1">
                    <label for="floatingInputGroup1">ISBN</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="price" placeholder="Prix" id="floatingInputGroup1">
                    <label for="floatingInputGroup1">Prix du livre</label>
                  </div>
                  <button type="submit" name="add" class="btn btn-primary">ADD BOOK</button>
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
    <script type="text/javascript">
      const addAuthor = document.getElementById("addAuthor");
      const authors = document.getElementById("authors")

      addAuthor.addEventListener("input", function() {
        if (addAuthor.value !== '') {
          authors.disabled = true;
        } else {
          authors.disabled = false;
        }
      });

      authors.addEventListener("change", function() {
        if (authors.value == "" && authors.selectIndex !== -1) {
          console.log("hello");
          addAuthor.disabled = false;
        } else {
          addAuthor.disabled = true;
        }
      })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
  </body>

  </html>