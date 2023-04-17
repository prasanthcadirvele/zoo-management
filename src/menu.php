<?php

    session_start();

// now you can use $_SESSION to access session variables
if (isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true) {
    

?>
    <!DOCTYPE html>
    <html>
    <body>
    <head>
        <title>Projet Zoo</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/navbar.css">
        <link rel="stylesheet" href="../css/menu.css">
    </head>
    <style>
        #header {
            padding: 20px;
            text-align: center;
            ba

        }

        #header img {
            max-width: 10%;
            height: auto;
            
        }

        #header h1 {
            margin-top: 10px;
            font-size: 36px;
            font-weight: bold;
            color: #333333;
        }
    </style>

    <br>
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

    <center>
        <h1><?php
            echo 'Bonjour '.$_SESSION['nom'].' '.$_SESSION['prenom'].' !'   ;
            ?></h1>
        <br>
        <p class="description">
            Bienvenue sur l'application de gestion de zoo ! Cette application vous permet d'ajouter, modifier et supprimer
            des animaux. Vous êtes actuellement sur la page d'accueil de l'application.
        </p>
    </center>
    </body>
    </html>
<?php
} else {
        // Set a message to display to the user
        $_SESSION['message'] = 'Veuillez vous connecter pour accéder le pateforme';

        // Redirect the user to the login page
        header('Location: index.php');
        exit;
}
?>



