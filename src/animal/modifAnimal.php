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

// create a new instance of DBManager class
$credentials = new Credentials();
$db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());

if(($_SERVER["REQUEST_METHOD"] == "GET")  && isset($_GET['animal_id'])){

    $animalId = $_GET['animal_id'];
    $animal = $db->getAnimalById($animalId);
    $races = $db->getAllRaces();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/ajout_animal.css">
    <title>Modifications des animaux</title>
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

    <form action="modifAnimal.php" method="POST" class="animal-form">
        <h3 class="form-title">Modifier un animal</h3>
        <input type="hidden" name="id" value="<?php echo $animal['id']; ?>" required>
        <label for="race_id">Race:</label>
        <select name="race_id" required class="input-field">
            <?php foreach($races as $race): ?>
                <option value="<?php echo $race['id']; ?>" <?php if($animal['race_id']==$race['id']) echo 'selected'; ?>>
                    <?php echo $race['race']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="date_naissance">Date de naissance:</label>
        <input type="date" name="date_naissance" value="<?php echo $animal['date_naissance']; ?>" required class="input-field">
        <br>
        <label for="sexe">Sexe:</label>
        <select name="sexe" required class="input-field">
            <option value="M" <?php if($animal['sexe']=='M') echo 'selected'; ?>>Mâle</option>
            <option value="F" <?php if($animal['sexe']=='F') echo 'selected'; ?>>Femelle</option>
        </select>
        <br>
        <label for="pseudo">Pseudo:</label>
        <input type="text" name="pseudo" value="<?php echo $animal['pseudo']; ?>" required class="input-field">
        <br>
        <label for="commentaire">Commentaire:</label>
        <textarea name="commentaire" class="input-field"><?php echo $animal['commentaire']; ?></textarea>
        <br>
        <input type="hidden" name="animal_id" value="<?php echo $animal["id"]?>">
        <input type="submit" value="Sauvegarder" class="submit-button">
        <center>
            <a href="listeAnimaux.php" class="back-button">Retour à la liste des animaux</a>
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

<?php
}
    else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $id = $_POST["animal_id"];
            $race_id = $_POST["race_id"];
            $date_naissance = $_POST["date_naissance"];
            $sexe = $_POST["sexe"];
            $pseudo = $_POST["pseudo"];
            $commentaire = $_POST["commentaire"];

            // Update animal in database
            $updateStatus = $db->updateAnimal($id, $race_id, $date_naissance, $sexe, $pseudo, $commentaire);
            if($updateStatus){
                $_SESSION['animal_update_status'] = 'La mise à jour a été effectuée avec succès';
                header("Location: listeAnimaux.php");
            }else{
                $_SESSION['animal_update_status'] = 'La mise à jour n\'a pas été effectuée avec succès.';
                header("Location: modifAnimal.php?animal_id=".$id);
            }
            exit();
    }
?>