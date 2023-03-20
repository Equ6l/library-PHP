<?php
// On recupere la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donn�es
include('../includes/config.php');

// Si l'utilisateur est déconnecté
// L'utilisateur est renvoyé vers la page de login : index.php
if (strlen($_SESSION['alogin']) == 0) {
    // L'utilisateur est renvoyé vers la page de login : index.php
    header("location:../.././php/adminlogin.php");
} else {
    if (isset($_GET['del'])) {
        // On recupere l'identifiant de la catégorie a supprimer
        $id = $_GET['del'];
        $status = $_GET['status'];
        $status ==  1 ? $status = 0 : $status = 1;
        // On prepare la requete de suppression
        $sql = "UPDATE tblcategory SET Status = :status WHERE id=:id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':status', $status);
        // On execute la requete
        $stmt->execute();

        // On informe l'utilisateur du resultat de loperation
        $_SESSION['delmsg'] = "La suppression de la catégorie a été réussie";
        // On redirige l'utilisateur vers la page manage-categories.php
        header('location:manage-categories.php');
    }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <title>Gestion de bibliothèque en ligne | Gestion categories</title>
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
    <!-- On affiche le titre de la page-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h4 class="header-line">Gestion des catégories</h4>
                </div>
            </div>
            <div class="row">
                <table class="table table-light text-center align-middle">
                    <thead>
                        <tr class="table-active">
                            <th>#</th>
                            <th>Nom</th>
                            <th>Status</th>
                            <th>Créé le</th>
                            <th>Mis à jour le</th>
                            <th>Opérations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tblcategory";
                        $stmt = $dbh->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
                        $counter = 0;

                        if ($stmt->rowCount() > 0) {
                            foreach ($result as $rows) {
                                $counter++; ?>
                                <tr>
                                    <td><?= $counter; ?></td>
                                    <td><?= $rows->CategoryName; ?></td>
                                    <td><?php if ($rows->Status === 1) {
                                            echo "<button type='button' class='btn btn-success'>Active</button>";
                                        } else if ($rows->Status === 0) {
                                            echo "<button type='button' class='btn btn-danger'>Inactive</button>";
                                        }  ?>
                                    </td>
                                    <td><?= $rows->CreationDate; ?></td>
                                    <td><?= $rows->UpdationDate ?></td>
                                    <td>
                                        <a href="edit-category.php?catid=<?php echo htmlentities($rows->id); ?>">
                                            <button type="button" class="btn btn-primary me-2"><i class="fa fa-edit"></i> Éditer</button>
                                        </a>
                                        <a href="manage-categories.php?del=<?php echo htmlentities($rows->id); ?>&status=<?= htmlentities($rows->Status) ?>" onclick="return confirm('Etes-vous sur(e) de vouloir supprimer ?');">
                                            <button class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</button>
                                        </a>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- On prevoit ici une div pour l'affichage des erreurs ou du succes de l'operation de mise a jour ou de suppression d'une categorie-->

    <!-- On affiche le formulaire de gestion des categories-->

    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('../includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>