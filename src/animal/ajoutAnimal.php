<?php
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerEmploye.php';

use config\Credentials;

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

    $raceOptions = $db->getAllRaces();


    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raceId = $_POST['race_id'];
        $dateNaissance = $_POST['date_naissance'];
        $sexe = $_POST['sexe'];
        $pseudo = $_POST['pseudo'];
        $commentaire = $_POST['commentaire'];

        $result = $db->addAnimal($raceId,$dateNaissance,$sexe,$pseudo,$commentaire);


        // Check if insert was successful
        if ($result) {
            $successMessage = "Animal ajouté avec succès";
        } else {
            $errorMessage = "Ajout d'animal échoué";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/ajout_animal.css">
    <title>Ajout animaux - PARC ZOO</title>
</head>
<body>
<div>
    <img src="../../images/logo.png" alt="Logo" class="logo-image">
    <nav class="navbar">
        <a class="nav-link" href="../menu.php">Home</a>

        <a class="nav-link" href="listeAnimaux.php">Animaux</a>
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
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="animal-form">
        <h3>Ajouter un Animal</h3>
        <label for="race_id">Race:</label>
        <select name="race_id" id="race_id" class="input-field">
            <?php foreach ($raceOptions as $raceOption) { ?>
                <option value="<?php echo $raceOption['id']; ?>"><?php echo $raceOption['race']; ?></option>
            <?php } ?>
        </select>
        <br>
        <label for="date_naissance">Date de naissance:</label>
        <input type="date" name="date_naissance" id="date_naissance" class="input-field">
        <br>
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe" class="input-field">
            <option value="M">M</option>
            <option value="F">F</option>
        </select>
        <br>
        <label for="pseudo">Pseudo:</label>
        <input type="text" name="pseudo" id="pseudo" class="input-field">
        <br>
        <label for="commentaire">Commentaire:</label>
        <input type="text" name="commentaire" id="commentaire" class="input-field">
        <br>
        <?php
        if(isset($successMessage)){
            echo '<p class="success-message">'.$successMessage.'</p>';
        }else if(isset($errorMessage)){
            echo '<p class="error-message">'.$errorMessage.'</p>';
        }
        ?>
        <input type="submit" value="Add Animal" class="submit-button">
        <center>
            <a href="listeAnimaux.php" class="back-button">Retour liste des animaux</a>
        </center>

    </form>
</div>


<div aria-label="Orange and tan hamster running in a metal wheel" role="img" class="wheel-and-hamster">
        <div class="wheel"></div>
        <div class="hamster">
            <div class="hamster__body">
                <div class="hamster__head">
                    <div class="hamster__ear"></div>
                    <div class="hamster__eye"></div>
                    <div class="hamster__nose"></div>
                </div>
                <div class="hamster__limb hamster__limb--fr"></div>
                <div class="hamster__limb hamster__limb--fl"></div>
                <div class="hamster__limb hamster__limb--br"></div>
                <div class="hamster__limb hamster__limb--bl"></div>
                <div class="hamster__tail"></div>
            </div>
        </div>
        <div class="spoke"></div>
    </div>
</body>
</html>

