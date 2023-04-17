<?php

use config\Credentials;
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerDirecteur.php';

$errorMessage = null;
$successMessage = null;


    session_start();


// now you can use $_SESSION to access session variables
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Set a message to display to the user
    $_SESSION['message'] = 'Veuillez vous connecter pour accéder le pateforme';

    // Redirect the user to the login page
    header('Location: ../index.php');
    exit;
}

$credentials = new Credentials();
$db = new DBManagerDirecteur($credentials->getServername(),$credentials->getUserDirecteur(), $credentials->getUserDirecteurPassword(), $credentials->getDatabaseName());


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['personnel_id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    $fonction = $_POST['fonction'];
    $salaire = $_POST['salaire'];

    $personnel = $db->getPersonnelByLogin($login);
    if ($personnel && $personnel['id'] != $id) {
        $_SESSION['update_personnel_status'] = "Erreur: Le login existe déjà.";
        header("Location: modifPersonnel.php?id=$id");
        exit;
    }

    $result = $db->updatePersonnel($id, $nom, $prenom, $date_naissance, $sexe, $login, $fonction, $salaire);
    if ($result) {
        $_SESSION['update_personnel_status'] = "Le personnel a été modifié avec succès.";
        header("Location: listePersonnel.php");
        exit;
    } else {
        $_SESSION['update_personnel_status'] = "Erreur: Le personnel n'a pas pu être modifié.";
        header("Location: modifPersonnel.php?id=$id");
        exit;
    }
}

    if(!isset($_GET['personnel_id']) || empty($_GET['personnel_id'])) {
        header('Location: listePersonnel.php');
    }

    $personnel = $db->getPersonnelById($_GET['personnel_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier Personnel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/ajout_personnel.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>
<body>
<div>
    <img src="../../images/logo.png" alt="Logo" class="logo-image">
    <nav class="navbar">
        <a class="nav-link" href="../menu.php">Home</a>

        <a class="nav-link" href="../animal/listeAnimaux.php">Animaux</a>
        <a class="nav-link" href="../ajoutespece.php">Ajouter un espèce</a>
        <a class="nav-link" href="../loc_animal/listeLocAnimaux.php">Location Animaux</a>
        <?php
        if ($_SESSION['role'] == "DIRECTEUR") {
            echo '<a class="nav-link" href="../personnel/listePersonnel.php">Personnel</a>';
        }
        ?>
        <a class="nav-link" href="../contact.php">Contact</a>
        <a class="nav-link" href="../deconnexion.php">Déconnexion</a>
    </nav>
</div>
<div class="container">
    <form class="personnel-form" method="post" action="modifPersonnel.php">
        <h2 class="form-title">Modifier un personnel</h2>
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" class="form-input" value="<?php echo $personnel['nom']; ?>" required>
        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" id="prenom" class="form-input" value="<?php echo $personnel['prenom']; ?>" required>
        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" name="date_naissance" id="date_naissance" class="form-input" value="<?php echo $personnel['date_naissance']; ?>" required>
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe" class="form-input" required>
            <option value="">--Choisissez le sexe--</option>
            <option value="H" <?php if ($personnel['sexe'] === 'H') echo 'selected'; ?>>Homme</option>
            <option value="F" <?php if ($personnel['sexe'] === 'F') echo 'selected'; ?>>Femme</option>
        </select>
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" class="form-input" value="<?php echo $personnel['login']; ?>" required>
        <label for="fonction">Fonction:</label>
        <select name="fonction" id="fonction" class="form-input" required>
            <option value="">--Choisissez la fonction--</option>
            <option value="DIRECTEUR" <?php if ($personnel['fonction'] === 'DIRECTEUR') echo 'selected'; ?>>Directeur</option>
            <option value="EMPLOYE" <?php if ($personnel['fonction'] === 'EMPLOYE') echo 'selected'; ?>>Employé</option>
        </select>
        <label for="salaire">Salaire:</label>
        <input type="number" name="salaire" id="salaire" class="form-input" value="<?php echo $personnel['salaire']; ?>" required>
        <?php
        if(isset($_SESSION['modif_personnel_status'])) {
            if($_SESSION['modif_personnel_status'] === 'Le personnel a été modifié avec succès') {
                echo '<p style="color: green;">'.$_SESSION['modif_personnel_status'].'</p>';
            } else {
                echo '<p style="color: red;">'.$_SESSION['modif_personnel_status'].'</p>';
            }
            unset($_SESSION['modif_personnel_status']);
        }
        ?>

        <input type="hidden" name="personnel_id" value="<?php echo $personnel['id']; ?>">
        <div class="form-actions">
            <br><br>
            <input type="submit" value="Modifier Personnel" class="form-btn">
            <center>
                <a href="listePersonnel.php" class="back-button">Retour à la liste des Personnels</a>
            </center>

        </div>
    </form>
</div>


</body>
</html>
