<?php

use config\Credentials;
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerEmploye.php';

$errorMessage = null;
$successMessage = null;


session_start();


// now you can use $_SESSION to access session variables
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Set a message to display to the user
    $_SESSION['message'] = 'Veuillez vous connecter pour accéder le plateforme';

    // Redirect the user to the login page
    header('Location: ../index.php');
    exit;
}



// create a new instance of DBManager class
$credentials = new Credentials();
$db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $animal_pseudo = $_POST['animal_pseudo'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_sortie = isset($_POST['date_sortie']) ? $_POST['date_sortie'] : null;

    // Check if animal exists
    $animal = $db->getAnimalByPseudo($animal_pseudo);
    if (!$animal) {
        $_SESSION['ajout_location_animal_status'] = 'Cet animal n\'existe pas.';
        header('Location: ajoutLocAnimal.php');
        exit;
    }

    $animal_id = $animal['id'];

    // Check if animal is already on location
    $loc_animal = $db->getLocAnimalByAnimalId($animal_id);
    if ($loc_animal) {
        $_SESSION['ajout_location_animal_status'] = 'Cet animal est déjà en location.';
        header('Location: ajoutLocAnimal.php');
        exit;
    }

    // Add new loc_animal
    $success = $db->addLocAnimal($animal_id, $date_arrivee, $date_sortie);

    if ($success) {
        $_SESSION['ajout_location_animal_status'] = 'Location ajoutée avec succès.';
    } else {
        $_SESSION['ajout_location_animal_status'] = 'Erreur lors de l\'ajout de la location.';
    }

    header('Location: ajoutLocAnimal.php');
    exit;
}


$animals = $db->getAllAnimaux();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajout Location Animal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/ajout_loc_animal.css">
    <link rel="stylesheet" href="../../css/navbar.css">

</head>
<body>

<div>
    <img src="../../images/logo.png" alt="Logo" class="logo-image">
    <nav class="navbar">
        <a class="nav-link" href="../menu.php">Home</a>

        <a class="nav-link" href="../animal/listeAnimaux.php">Animaux</a>
        <a class="nav-link" href="../ajoutespece.php">Ajouter une espèce</a>
        <a class="nav-link" href="listeLocAnimaux.php">Location Animaux</a>
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
    <form method="POST" action="ajoutLocAnimal.php">
        <label for="animal">Animal:</label>
        <select id="animal" name="animal_pseudo">
            <?php
                for ($i = 0; $i < count($animals); $i++) {
                    $pseudo = $animals[$i]['pseudo'];
                    echo '<option value="' . $pseudo . '">' . $pseudo . '</option>';
                }
            ?>
        </select>


        <label for="date_arrivee">Date d'arrivée:</label>
        <input type="date" id="date_arrivee" name="date_arrivee" required>

        <label for="date_sortie">Date de sortie:</label>
        <input type="date"   id="date_sortie" name="date_sortie">

        <?php if(isset($_SESSION['ajout_location_animal_status'])){
            if($_SESSION['ajout_location_animal_status'] === 'Location ajoutée avec succès.'){
            ?>
        <p style="color: green">
            <?php
            }else{
            ?>
        <p style="color: red">
            <?php
            }
            echo $_SESSION['ajout_location_animal_status'];
            $_SESSION['ajout_location_animal_status']= null;
            ?>
        </p>
        <?php } ?>


        <input type="submit" value="Ajouter">
        <p><a href="listeLocAnimaux.php" class="back-button">Retour à liste des animaux</a></p>

    </form>
</div>
</body>
</html>

