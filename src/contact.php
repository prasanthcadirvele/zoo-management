<?php

session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/contact.css">
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
    <title>Contact</title>
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
            echo '<a class="nav-link" href="../personnel/listePersonnel.php">Personnel</a>';
        }
        ?>
        <a class="nav-link" href="contact.php">Contact</a>
        <a class="nav-link" href="deconnexion.php">Déconnexion</a>
    </nav>
</div>
<div class="card-container">
    <div class="card-client">
        <div class="user-picture"></div>
        <p class="name-client">Prasanth Cadirvéle<br>
            <span>BTS SIO 1B <br> Lycée Parc de Vilgénis</span>
        </p>
    </div>
    <div class="card-client">
        <div class="user-picture"></div>
        <p class="name-client">Elias Merimi<br>
            <span>BTS SIO 1B <br> Lycée Parc de Vilgénis</span>
        </p>
    </div>
</div>

</body>
</html>
