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

      // On recupere l'identifiant du livre que l'on souhaite afficher
      $bookId = intval($_GET['bookid']);

      // On affiche le livre qui a été sélectionné
      $sql3 = "SELECT * FROM tblbooks WHERE id = :id ";
      $stmt = $dbh->prepare($sql3);
      $stmt->bindParam(':id', $bookId);

      $stmt->execute();
      $result3 = $stmt->fetch(PDO::FETCH_OBJ);

      if (isset($_POST['update'])) {

            $param = [
                  'book' => $_POST['bookName'],
                  'category' => $_POST['category'],
                  'author' => $_POST['author'],
                  'isbn' => $_POST['isbn'],
                  'price' => $_POST['price'],
                  'bookid' => $bookId
            ];

            $sql = "UPDATE tblbooks 
            SET BookName=:book, CatId=:category, AuthorId=:author, ISBNNumber=:isbn, BookPrice=:price 
            WHERE id=:bookid";
            $stmt = $dbh->prepare($sql);

            foreach ($param as $key => &$values) {
                  $stmt->bindParam(':' . $key, $values);
            }

            $stmt->execute();
      }

?>

      <!DOCTYPE html>
      <html>

      <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

            <title>Gestion de bibliothèque en ligne | Livres</title>
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
                                    <h4 class="header-line">Éditer un livre</h4>
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
                                                            <label for="floatingInputGroup1"><?= 'Nom du livre : ' . $result3->BookName ?></label>

                                                      </div>
                                                      <select class="form-select form-select-md mb-3" aria-label="Default select example" name="category">
                                                            <option selected><?= 'Catégorie : ' . $result3->CatId ?></option>
                                                            <?php while ($donnees = $result1->fetch(PDO::FETCH_ASSOC)) { ?>
                                                                  <option value="<?= $donnees['id']; ?>"><?= $donnees['CategoryName'];
                                                                                                      } ?></option>
                                                      </select>
                                                      <select class="form-select form-select-md mb-3" aria-label="Default select example" name="author" id="authors">
                                                            <option value=""><?= 'Auteur : ' . $result3->AuthorId ?></option>
                                                            <?php while ($donnees = $result2->fetch(PDO::FETCH_ASSOC)) { ?>
                                                                  <option value="<?= $donnees['id']; ?>"><?= $donnees['AuthorName'];
                                                                                                      }
                                                                                                } ?></option>
                                                      </select>
                                                      <div class="form-floating mb-3">
                                                            <input type="text" class="form-control" name="isbn" placeholder="ISBN" id="floatingInputGroup1">
                                                            <label for="floatingInputGroup1"><?= 'ISBN : ' . $result3->ISBNNumber ?></label>
                                                      </div>
                                                      <div class="form-floating mb-3">
                                                            <input type="text" class="form-control" name="price" placeholder="Prix" id="floatingInputGroup1">
                                                            <label for="floatingInputGroup1"><?= 'Prix : ' . $result3->BookPrice . ' €' ?></label>
                                                      </div>
                                                      <button type="submit" name="update" class="btn btn-primary">UPDATE BOOK</button>
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