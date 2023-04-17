<?php
use config\Credentials;
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerDirecteur.php';


    session_start();



// Redirect to login page if user is not logged in or user role is not DIRECTEUR
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'DIRECTEUR') {
    header("location: ../index.php");
    exit;
}

// create a new instance of DBManager class
$credentials = new Credentials();
$db = new DBManagerDirecteur($credentials->getServername(),$credentials->getUserDirecteur(), $credentials->getUserDirecteurPassword(), $credentials->getDatabaseName());

// Get all personnel
$personnels = $db->getAllPersonnels();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Personnel Table</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../../css/liste_personnel.css">
    <link rel="stylesheet" type="text/css" href="../../css/navbar.css">
    <script>
        function searchTable() {
            // Declare variables
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector(".table");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those that don't match the search query
            var found = false;
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // get the second td element (index 1) which contains the 'Nom'
                td2 = tr[i].getElementsByTagName("td")[2]; // get the third td element (index 2) which contains the 'Prénom'
                if (td && td2) {
                    txtValue = td.textContent + td2.textContent; // concatenate the text content of both td elements
                    if (txtValue.toUpperCase().indexOf(filter) > -1) { // check if the concatenated string matches the search query
                        tr[i].style.display = "";
                        found = true;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
            // Display message if no results found
            if (!found) {
                var noResults = document.createElement('p');
                noResults.innerHTML = "Aucun résultat trouvé";
                table.parentNode.insertBefore(noResults, table.nextSibling);
            } else {
                // Remove message if results are found
                var noResults = table.parentNode.querySelector('p');
                if (noResults) {
                    noResults.parentNode.removeChild(noResults);
                }
            }liste_personnel.css
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
    <h1>Personnel Table</h1>
    <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Rechercher...">
    <button style="position: absolute;right: 1%;" onclick="window.location.href='ajoutPersonnel.php'">Ajouter un personnel</button>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date de naissance</th>
            <th>Sexe</th>
            <th>Login</th>
            <th>Fonction</th>
            <th>Salaire</th>
            <th>Actions</th>
        </tr>
        <?php
        // Loop through personnel and display in table
        foreach ($personnels as $personnel) {
            echo "<tr>";
            echo "<td>" . $personnel['id'] . "</td>";
            echo "<td>" . $personnel['nom'] . "</td>";
            echo "<td>" . $personnel['prenom'] . "</td>";
            echo "<td>" . $personnel['date_naissance'] . "</td>";
            echo "<td>" . $personnel['sexe'] . "</td>";
            echo "<td>" . $personnel['login'] . "</td>";
            echo "<td>" . $personnel['fonction'] . "</td>";
            echo "<td>" . $personnel['salaire'] . "</td>";
            echo "<td>";
            echo "</form>";
            echo "<form method='get' action='modifPersonnel.php'>";
            echo "<input type='hidden' name='personnel_id' value=". $personnel['id'].">";
            echo "<button type='submit'>Modifier</button>";
            echo "</form>";
            echo "<form method='post' action='deletePersonnel.php'>";
            echo "<input type='hidden' name='personnel_id' value='" . $personnel['id'] . "'>";
            echo "<button type='submit'>Supprimer</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
