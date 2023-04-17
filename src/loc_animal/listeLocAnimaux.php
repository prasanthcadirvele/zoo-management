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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Location des Animaux</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/liste_loc_animaux.css">
    <link rel="stylesheet" type="text/css" href="../../css/navbar.css">
    <script>
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search");
            filter = input.value.toLowerCase();
            table = document.getElementById("loc_animaux");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Change the index to match the column where the animal name (pseudo) is located
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
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

<input id="search" type="text" placeholder="Rechercher par animal (pseudo)" onkeyup="searchTable()">
<button style="position: absolute;right: 1%;" onclick="window.location.href='ajoutLocAnimal.php'">Ajout une location</button>
<table id="loc_animaux" border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Animal</th>
        <th>Date d'arrivée</th>
        <th>Date de sortie</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>

    <?php

    // create a new instance of DBManager class
    $credentials = new Credentials();
    $db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());

    // Get all data from the loc_animaux table
    $rows = $db->getAllLocAnimaux();

    // Output data of each row
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["pseudo"] . "</td>";
        echo "<td>" . $row["date_arrivee"] . "</td>";
        echo "<td>" . $row["date_sortie"] . "</td>";
        echo "<td>";
        echo "</form>";
        echo "<form method='get' action='modifLocAnimal.php'>";
        echo "<input type='hidden' name='loc_animal_id' value=". $row['id'].">";
        echo "<button type='submit'>Modifier</button>";
        echo "</form>";
        echo "<form method='post' action='deleteLocAnimal.php'>";
        echo "<input type='hidden' name='loc_animal_id' value='" . $row['id'] . "'>";
        echo "<button type='submit'>Supprimer</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

