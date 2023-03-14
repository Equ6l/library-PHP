<?php
// On récupère la session courante
session_start();

// On inclue le fichier de configuration et de connexion a la base de donnees
include('../includes/config.php');

// On invalide le cache de session
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
     $_SESSION['login'] = '';
}

if (true === isset($_POST['login'])) {
     // Apres la soumission du formulaire de login ($_POST['login'] existe - voir pourquoi plus bas)
     // On verifie si le code captcha est correct en comparant ce que l'utilisateur a saisi dans le formulaire
     // $_POST["vercode"] et la valeur initialisee $_SESSION["vercode"] lors de l'appel a captcha.php (voir plus bas)
     if ($_POST['vercode'] != $_SESSION['vercode']) {
          // Le code est incorrect on informe l'utilisateur par une fenetre pop_up
          echo "<script>alert('Code de vérification incorrect')</script>";
     } else {
          // Le code est correct, on peut continuer
          // On recupere le mail et le numero de portable de l'utilisateur saisi dans le formulaire
          $emailid = $_POST['emailId'];
          // et le nouveau mot de passe que l'on encode (fonction password_hash)
          $newPassword = password_hash($_POST["confirmPassword"], PASSWORD_DEFAULT);

          // On cherche en base le lecteur avec cet email et ce numero de tel dans la table tblreaders
          $sql = "SELECT * FROM tblreaders WHERE EmailId = :emailId";
          $stmt = $dbh->prepare($sql);
          $stmt->bindParam(':emailId', $emailid);
          $stmt->execute();

          $result = $stmt->fetch(PDO::FETCH_OBJ);
          // Si la requête SQL renvoie un résultat, on met à jour le mot de passe de l'utilisateur
          if (!empty($result)) {

               // On met à jour le champ "Password" avec le nouveau mot de passe haché
               $sql = "UPDATE tblreaders SET Password = :newPassword WHERE EmailId = :emailId";
               $stmt = $dbh->prepare($sql);
               $stmt->bindParam(":newPassword", $newPassword);
               $stmt->bindParam(":emailId", $emailid);
               $stmt->execute();

               // On informe l'utilisateur que le mot de passe a été mis à jour avec succès
               echo "<h6>Le mot de passe a été mis à jour avec succès</h6>";
          } else {
               // Sinon, on informe l'utilisateur que les informations saisies ne correspondent à aucun enregistrement dans la base de données
               echo "<h6>Les informations saisies ne correspondent pas, veuillez réessayez</h6>";
          }
     }
}
?>

<!DOCTYPE html>
<html lang="FR">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

     <title>Gestion de bibliotheque en ligne | Recuperation de mot de passe </title>
     <!-- BOOTSTRAP CORE STYLE  -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- FONT AWESOME STYLE  -->
     <link href="../assets/css/font-awesome.css" rel="stylesheet" />
     <!-- CUSTOM STYLE  -->
     <link href="../assets/css/style.css" rel="stylesheet" />

     <script type="text/javascript">
          // On cree une fonction nommee valid() qui verifie que les deux mots de passe saisis par l'utilisateur sont identiques.
          function valid() {
               let input = document.querySelectorAll("input");
               if (input[1].value == input[2].value) {
                    // TRUE si les mots de passe saisis dans le formulaire sont identiques
                    return true
               } else {
                    // FALSE sinon
                    return false
               }
          }
     </script>

</head>

<body>
     <?php include('../includes/header.php'); ?>
     <div class="container">
          <div class="row text-center m-3">
               <div class="col-12">

                    <h3>RÉCUPERATION DE MOT DE PASSE</h3>
               </div>
          </div>
          <div class="row justify-content-center">
               <div class="col-xs-12 col-sm-6 col-md-6 col-lg-8">

                    <form action="user-forgot-password.php" method="POST" onSubmit="return valid()">

                         <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                                   <label for="emailId" class="form-label">Entrez votre email</label>
                                   <input type="text" name="emailId" class="form-control" required>
                                   <span id="test"></span>
                              </div>
                         </div>
                         <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                                   <label for="password" class="form-label">Entrez votre nouveau mot de passe</label>
                                   <input type="password" name="password" class="form-control" required>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                                   <label for="confirmPassword" class="form-label">Vérifiez votre mot de passe</label>
                                   <input type="password" name="confirmPassword" class="form-control" required>
                              </div>
                         </div>
                         <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6  mb-2">
                                   <label class="form-label">Code de vérification</label>
                                   <input type="text" name="vercode" class="form-control" required>
                              </div>
                         </div>
                         <div class="row">
                              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 mb-3">
                                   <!-- A la suite de la zone de saisie du captcha, on insère l'image créée par captcha.php : <img src="captcha.php">  -->
                                   <img src="captcha.php">
                              </div>
                         </div>
                         <div class="mb-3">
                              <input type="submit" name="login" class="btn btn-danger" value="CHANGE">
                         </div>
                    </form>
               </div>
          </div>
     </div>
     <?php include('../includes/footer.php'); ?>
     <!-- FOOTER SECTION END-->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>