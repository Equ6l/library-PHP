<?php
// On démarre ou on récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion à la base de données
include('../includes/config.php');

if (strlen($_SESSION['alogin']) === 0) {
    // Si l'utilisateur n'est logué ($_SESSION['alogin'] est vide)
    // On le redirige vers la page d'accueil
    header("location:../.././php/adminlogin.php");
} else {
    // Sinon on affiche la liste des lecteurs de la table tblreaders
    // On récupère tous les lecteurs dans la base de données
    $sql = "SELECT * FROM tblreaders";
    $stmt = $dbh->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Lors d'un click sur un bouton "inactif", on r�cup�re la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['inid']
    // et on met à jour le statut (0) dans la table tblreaders pour cet identifiant de lecteur
    if (isset($_GET['inid'])) {
        $id = $_GET['inid'];
        $status = 0;
        $sql = "update tblreaders set Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-readers.php');
    }

    // Lors d'un click sur un bouton "actif", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['id']
    // et on met à jour le statut (1) dans  table tblreaders pour cet identifiant de lecteur
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $status = 1;
        $sql = "update tblreaders set Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-readers.php');
    }


    // Lors d'un click sur un bouton "supprimer", on récupère la valeur de l'identifiant
    // du lecteur dans le tableau $_GET['del']
    // et on met à jour le statut (2) dans la table tblreaders pour cet identifiant de lecteur
    if (isset($_GET['del'])) {
        $id = $_GET['del'];
        $status = 2;
        $sql = "UPDATE tblreaders SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:reg-readers.php');
    }
?>

    <!DOCTYPE html>
    <html lang="FR">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <title>Gestion de bibliothèque en ligne | Reg lecteurs</title>
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
        <!-- Titre de la page (Gestion du Registre des lecteurs) -->

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
                                <th>ID lecteurs</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Portable</th>
                                <th>Date d'enregistrement</th>
                                <th>Status</th>
                                <th>Opérations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $counter = 0;
                            foreach ($result as $rows) {
                                $counter++; ?>
                                <tr>
                                    <td><?= $counter; ?></td>
                                    <td><?= $rows->ReaderId ?></td>
                                    <td><?= $rows->FullName ?></td>
                                    <td><?= $rows->EmailId ?></td>
                                    <td><?= $rows->MobileNumber ?></td>
                                    <td><?= $rows->RegDate ?></td>
                                    <td><?php echo ($rows->Status === 0) ? "Inactif" : (($rows->Status === 2) ? "Bloqué" : "Actif") ?></td>
                                    <td>
                                        <?php if ($rows->Status === 1) { ?>
                                            <a href="reg-readers?inid=<?php echo $rows->id ?>" onclick="return confirm('Etes-vous sûr.e de votre choix ?')">
                                                <button type="button" class="btn btn-success me-2">Actif</button>
                                            </a>
                                        <?php } elseif ($rows->Status === 0) { ?>
                                            <a href="reg-readers?id=<?php echo $rows->id ?>" onclick="return confirm('Etes-vous sûr.e de votre choix ?')">
                                                <button type="button" class="btn btn-warning me-2">Inactif</button>
                                            </a>
                                        <?php } ?>
                                        <?php if ($rows->Status != 2) { ?>
                                            <a href="reg-readers.php?del=<?php echo $rows->id ?>" onclick="return confirm('Etes-vous sûr.e de votre choix ?')">
                                                <button type="button" class="btn btn-danger me-2"><i class="fa fa-trash-o"></i> Delete</button>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!--On insère ici le tableau des lecteurs.
       On gère l'affichage des boutons Actif/Inactif/Supprimer en fonction de la valeur du statut du lecteur -->

            <!-- CONTENT-WRAPPER SECTION END-->
            <?php include('../includes/footer.php'); ?>
            <!-- FOOTER SECTION END-->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>

    </html>