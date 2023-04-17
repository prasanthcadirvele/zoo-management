<?php

use config\Credentials;
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerDirecteur.php';

$errorMessage = null;
$successMessage = null;


    session_start();


// now you can use $_SESSION to access session variables
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] != 'DIRECTEUR') {
    // Set a message to display to the user
    $_SESSION['message'] = 'Veuillez vous connecter pour accéder le pateforme';

    // Redirect the user to the login page
    header('Location: ../index.php');
    exit;
}

// create a new instance of DBManager class
$credentials = new Credentials();
$db = new DBManagerDirecteur($credentials->getServername(),$credentials->getUserDirecteur(), $credentials->getUserDirecteurPassword(), $credentials->getDatabaseName());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    $fonction = $_POST['fonction'];
    $salaire = $_POST['salaire'];

    // Check if the personnel already exists
    $existing_personnel = $db->getPersonnelByLogin($login);
    if ($existing_personnel !== null) {
        $_SESSION['add_personnel_status'] = 'Erreur: Un personnel avec ce login existe déjà';
    } else {
        // Insert the personnel
        $success = $db->addPersonnel($nom, $prenom, $date_naissance, $sexe, $login, $mdp, $fonction, $salaire);
        if ($success) {
            $_SESSION['add_personnel_status'] = 'Le personnel a été ajouté avec succès.';
        } else {
            $_SESSION['add_personnel_status'] = 'Erreur: Impossible d\'ajouter le personnel';
        }
    }

    // Redirect to the personnel list page
    header('Location: ajoutPersonnel.php');
    exit();
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Personnel</title>
    <link rel="stylesheet" type="text/css" href="../../css/ajout_personnel.css">
    <link rel="stylesheet" type="text/css" href="../../css/navbar.css">
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
    <form class="personnel-form" method="post" action="ajoutPersonnel.php">
        <h2 class="form-title">Ajouter un personnel</h2>
        <label for="nom">Nom:</label>
        <input type="text" name="nom" id="nom" class="form-input" required>
        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" id="prenom" class="form-input" required>
        <label for="date_naissance">Date de Naissance:</label>
        <input type="date" name="date_naissance" id="date_naissance" class="form-input" required>
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe" class="form-input" required>
            <option value="">--Choisissez le sexe--</option>
            <option value="H">Homme</option>
            <option value="F">Femme</option>
        </select>
        <label for="login">Login:</label>
        <input type="text" name="login" id="login" class="form-input" required>
        <label for="mdp">Mot de passe:</label>
        <input type="password" name="mdp" id="mdp" class="form-input" required>
        <label for="fonction">Fonction:</label>
        <select name="fonction" id="fonction" class="form-input" required>
            <option value="DIRECTEUR">Directeur</option>
            <option value="EMPLOYE">Employé</option>
        </select>
        <label for="salaire">Salaire:</label>
        <input type="number" name="salaire" id="salaire" class="form-input" required>
        <?php

        if (isset($_SESSION['add_personnel_status'])) {
            $message = $_SESSION['add_personnel_status'];
            $color = $message === 'Le personnel a été ajouté avec succès' ? 'green' : 'red';
            echo '<p style="color: '.$color.'">'.$message.'</p>';
        }

        unset($_SESSION['add_personnel_status']); // Clear message after displaying it
        ?>
        <div class="form-actions">
            <br><br>
            <input type="submit" value="Ajouter" class="form-btn">
            <center>
                <a href="listePersonnel.php" class="back-button">Retour à la liste des Personnels</a>
            </center>

        </div>
    </form>
</div>
</body>
</html>
