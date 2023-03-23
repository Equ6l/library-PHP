<?php
session_start();

include('../includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    // L'utilisateur est renvoyé vers la page de login : index.php
    header("location:../.././php/adminlogin.php");
} else {

?>
    <!DOCTYPE html>
    <html lang="FR">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

        <title>Gestion de bibliothèque en ligne | Gestion des sorties</title>
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
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h4 class="header-line">Gestion des sorties</h4>
                    </div>
                </div>
                <div class="row">
                    <table class="table table-light text-center align-middle">
                        <thead>
                            <tr class="table-active">
                                <th>#</th>
                                <th>Lecteur</th>
                                <th>Titre</th>
                                <th>ISBN</th>
                                <th>Sortie le</th>
                                <th>Retourné le</th>
                                <th>Opérations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sql = "SELECT tblreaders.FullName, tblbooks.BookName, tblbooks.ISBNNumber, tblissuedbookdetails.id, tblissuedbookdetails.IssuesDate, tblissuedbookdetails.ReturnDate 
                        FROM ((tblissuedbookdetails
                        JOIN tblreaders 
                        ON tblissuedbookdetails.ReaderID = tblreaders.ReaderId)
                        JOIN tblbooks 
                        ON tblissuedbookdetails.BookId = tblbooks.id)";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                            $counter = 0;

                            if ($stmt->rowCount() > 0) {
                                foreach ($result as $rows) {
                                    $counter++; ?>
                                    <tr>
                                        <td><?= $counter; ?></td>
                                        <td><?= $rows->FullName ?></td>
                                        <td><?= $rows->BookName ?></td>
                                        <td><?= $rows->ISBNNumber ?></td>
                                        <td><?= $rows->IssuesDate ?></td>
                                        <td><?php echo ($rows->ReturnDate !== null) ? $rows->ReturnDate : "Non retourné"; ?></td>
                                        <td><a href="edit-issue-book.php?rdid=<?php echo $rows->id ?>">
                                                <button type="button" class="btn btn-primary me-2"><i class="fa fa-edit"></i> Éditer</button>
                                            </a>
                                        </td>
                                    </tr>
                            <?php }
                            } ?>
                        </tbody>
                    </table>
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