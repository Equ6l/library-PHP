<?php
session_start();

include('../includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
      // L'utilisateur est renvoyé vers la page de login : index.php
      header("location:../.././php/adminlogin.php");
} else {


      if (isset($_POST['edit'])) {
            $rdid = intval($_GET['rdid']);
            $rDate = $_POST['returnDate'];
            $rStatus = 1;

            $sql = "UPDATE tblissuedbookdetails SET ReturnDate=:rdate,ReturnStatus=:rstatus WHERE id=:rdid";
            $stmt = $dbh->prepare($sql);

            $stmt->bindParam(':rdate', $rDate, PDO::PARAM_STR);
            $stmt->bindParam(':rstatus', $rStatus, PDO::PARAM_STR);
            $stmt->bindParam(':rdid', $rdid, PDO::PARAM_STR);

            $stmt->execute();

            $_SESSION['msg'] = "Le livre est bien retourne";
            header('location:manage-issued-books.php');
      }

      $rdid = intval($_GET['rdid']);
      $sql = "SELECT tblreaders.FullName, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.id, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate 
                        FROM ((tblissuedbookdetails
                        JOIN tblreaders 
                        ON tblissuedbookdetails.ReaderID = tblreaders.ReaderId)
                        JOIN tblbooks 
                        ON tblissuedbookdetails.BookId = tblbooks.id)
                        WHERE tblissuedbookdetails.id=:rdid";
      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':rdid', $rdid, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_OBJ);
?>
      <!DOCTYPE html>
      <html lang="FR">

      <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

            <title>Gestion de bibliothèque en ligne | Sorties</title>
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
                                    <h4 class="header-line">Éditer un livre emprunté</h4>
                              </div>
                        </div>
                        <div class="row justify-content-center">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <div class="card">
                                          <h6 class="card-header">
                                                <strong>Informations du livre emprunté</strong>
                                          </h6>
                                          <div class="card-body">
                                                <form action="" method="POST">
                                                      <div class="mb-3">
                                                            <input type="text" class="form-control" placeholder="<?= $result->FullName ?>" disabled>
                                                      </div>
                                                      <div class="mb-3">
                                                            <input type="text" class="form-control" name="bookName" placeholder="<?= $result->BookName ?>" disabled>
                                                      </div>
                                                      <div class="mb-3">
                                                            <input type="text" class="form-control" name="isbn" placeholder="<?= $result->ISBNNumber ?>" disabled>
                                                      </div>
                                                      <div class="mb-3">
                                                            <label class="form-label">Emprunté le :</label>
                                                            <input type="datetime-local" class="form-control" name="issuesDate" value="<?= $result->IssuesDate ?>" disabled>
                                                      </div>
                                                      <?php if ($result->ReturnDate === null) { ?>
                                                            <div class="mb-3">
                                                                  <label for="form-label">Non retourné</label>
                                                                  <input type="datetime-local" class="form-control" name="returnDate">
                                                            </div>
                                                            <button type="submit" name="edit" class="btn btn-primary">EDIT</button>
                                                      <?php } else { ?>
                                                            <div class="mb-3">
                                                                  <input type="datetime-local" class="form-control" name="returnDate" value="<?= $result->ReturnDate ?>" disabled>
                                                            </div>
                                                            <button type="submit" name="edit" class="btn btn-primary" disabled>EDIT</button>
                                                      <?php } ?>
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
<?php
} ?>