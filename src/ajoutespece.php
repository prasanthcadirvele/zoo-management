<?php
require_once '../config/Credentials.php';
require_once '../repository/DBManagerEmploye.php';

use config\Credentials;

$errorMessage = null;
$successMessage = null;


    session_start();


// now you can use $_SESSION to access session variables
if (isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true) {

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $race = $_POST['race'];
        $nourriture = $_POST['nourriture'];
        $duree_vie = $_POST['duree_vie'];
        $aquatique = isset($_POST['aquatique']) ? 1 : 0;

        // create a new instance of DBManager class
        $credentials = new Credentials();
        $db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());

        // call addEspece function to add data to the table
        $result = $db->addEspece($race, $nourriture, $duree_vie, $aquatique);

        if($result){
            $successMessage = "Espèce ajoutée avec succès!";
        }else{
            $errorMessage = "Échec de l'ajout d'espèce. Veuillez réssayer plus tard.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/ajout_espece.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <title>Ajout d'une nouvel espece</title>
</head>
<body>
<div>
    <img src="../images/logo.png" alt="Logo" class="logo-image">
    <nav class="navbar">
        <a class="nav-link" href="menu.php">Home</a>

        <a class="nav-link" href="animal/listeAnimaux.php">Animaux</a>
        <a class="nav-link" href="ajoutespece.php">Ajouter un espèce</a>
        <a class="nav-link" href="loc_animal/listeLocAnimaux.php">Location Animaux</a>
        <?php
        if ($_SESSION['role'] == "DIRECTEUR") {
            echo '<a class="nav-link" href="personnel/listePersonnel.php">Personnel</a>';
        }
        ?>
        <a class="nav-link" href="contact.php">Contact</a>
        <a class="nav-link" href="deconnexion.php">Déconnexion</a>
    </nav>
</div>
    <div class="login-box2">
        <div class="container">
            <form action="" method="POST" class="espece-form">
                <h3>Ajouter une espece</h3>
                <label for="race">Race:</label>
                <input type="text" id="race" name="race" required><br><br>
                <label for="nourriture">Nourriture:</label>
                <select id="nourriture" name="nourriture" required>
                    <option value="">-- Select --</option>
                    <option value="Carnivore">Carnivore</option>
                    <option value="Herbivore">Herbivore</option>
                    <option value="Omnivore">Omnivore</option>
                </select><br><br>
                <label for="duree_vie">Durée de Vie:</label>
                <input type="number" id="duree_vie" name="duree_vie" required><br><br>
                <label for="aquatique">Aquatique:</label>
                <input type="checkbox" id="aquatique" name="aquatique" value="1"><br><br>
                <?php
                if(isset($successMessage)){
                    echo '<p style="color:green;text-align:center;">'.$successMessage.'</p>';
                } elseif (isset($errorMessage)){
                    echo '<p style="color:red;text-align:center;">'.$errorMessage.'</p>';
                }
                ?>
                <input type="submit" value="Submit">
                <p><a href="menu.php" class="back-button">Retour au menu principal</a></p>
            </form>
        </div>

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

}else{
    // Set a message to display to the user
    $_SESSION['message'] = 'Veuillez vous connecter pour accéder le pateforme';

    // Redirect the user to the login page
    header('Location: index.php');
    exit;
}

?>