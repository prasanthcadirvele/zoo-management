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
    $_SESSION['message'] = 'Veuillez vous connecter pour accéder le pateforme';

    // Redirect the user to the login page
    header('Location: ../index.php');
    exit;
}

$credentials = new Credentials();
$db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());

if(($_SERVER["REQUEST_METHOD"] == "GET")  && isset($_GET['loc_animal_id'])){

    $animals = $db->getAllAnimaux();
    $locAnimal = $db->getLocAnimalById($_GET['loc_animal_id']);
    $select_animal_pseudo = $db->getAnimalById($locAnimal['animal_id']);
    $select_animal_pseudo = $select_animal_pseudo['pseudo'];
?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Modification Location Animal</title>
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
            <a class="nav-link" href="../ajoutespece.php">Ajouter un espèce</a>
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
        <form method="POST" action="modifLocAnimal.php">
            <label for="animal">Animal:</label>
            <select id="animal" name="animal_pseudo">
            <?php
                for ($i = 0; $i < count($animals); $i++) {
                    $pseudo = $animals[$i]['pseudo'];
                    if ($pseudo === $select_animal_pseudo) {
                        echo '<option value="' . $pseudo . '" selected="selected">' . $pseudo . '</option>';
                    } else {
                        echo '<option value="' . $pseudo . '">' . $pseudo . '</option>';
                    }
                }
            ?>

            <label for="date_arrivee">Date d'arrivée:</label>
            <input type="date" id="date_arrivee" name="date_arrivee" required value="<?php echo $locAnimal['date_arrivee']; ?>">

            <label for="date_sortie">Date de sortie:</label>
            <input type="date" id="date_sortie" name="date_sortie" value="<?php echo $locAnimal['date_sortie']; ?>">

            <?php if(isset($_SESSION['modification_location_animal_status'])){
                if($_SESSION['modification_location_animal_status'] === 'La location a été modifiée avec succès.'){
                    ?>
                    <p style="color: green">
                    <?php
                }else{
                    ?>
                    <p style="color: red">
                    <?php
                }
                echo $_SESSION['modification_location_animal_status'];
                $_SESSION['modification_location_animal_status']= null;
                ?>
                </p>
            <?php } ?>


            <input type="hidden" name="loc_animal_id" value="<?php echo $locAnimal['id']; ?>">
            <input type="submit" value="Modifier">
            <p><a href="listeLocAnimaux.php" class="back-button">Retour à liste des animaux</a></p>

        </form>
    </div>
    </body>
    </html>


    <?php
}

else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the values from the form
    $loc_animal_id = $_POST['loc_animal_id'];
    $animal_pseudo = $_POST['animal_pseudo'];
    $date_arrivee = $_POST['date_arrivee'];
    $date_sortie = $_POST['date_sortie'];

    $animal = $db->getAnimalByPseudo($animal_pseudo);


    // Update the locanimal
    $success = $db->updateLocAnimal($loc_animal_id, $animal['id'], $date_arrivee, $date_sortie);

    // Set the status message
    if ($success) {
        $_SESSION['loc_animal_update_status'] = "La location a été modifiée avec succès.";
    } else {
        $_SESSION['loc_animal_update_status'] = "La modification de la location a échoué.";
    }

    // Redirect to the list page
    header("Location: listeLocAnimaux.php");
    exit();
}

