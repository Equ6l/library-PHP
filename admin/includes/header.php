<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php" aria-expanded="false">
                            Tableau de bord
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            Catégories
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-category.php">Ajouter une catégorie</a></li>
                            <li><a class="dropdown-item" href="manage-categories.php">Gérer les catégories</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            Auteurs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-author.php">Ajouter un auteur</a></li>
                            <li><a class="dropdown-item" href="manage-authors.php">Gérer les auteurs</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            Livres
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-book.php">Ajouter un livre</a></li>
                            <li><a class="dropdown-item" href="manage-books.php">Gérer les livres</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            Sorties
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-issue-book.php">Ajouter une sortie</a></li>
                            <li><a class="dropdown-item" href="manage-issued-books.php">Gérer les sorties</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            Lecteurs
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="add-reader.php">Ajouter un lecteur</a></li>
                            <li><a class="dropdown-item" href="reg-students.php">Gérer les lecteurs</a></li>
                            <li><a class="dropdown-item" href="change-password.php">Modifier le mot de passe</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="right-div">
                    <a href="logout.php" class="btn btn-danger pull-right">DÉCONNEXION</a>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>