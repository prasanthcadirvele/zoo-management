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
<html lang="fr">
<head>
	<title>Liste Animaux</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../../css/liste_animaux.css">
	<link rel="stylesheet" href="../../css/navbar.css">
	<script>
		function searchAnimal() {
			var input = document.getElementById("search-input");
			var filter = input.value.toUpperCase();
			var table = document.getElementById("animal-table");
			var tr = table.getElementsByTagName("tr");

			// Remove any existing "No results" rows
			var noResultsRows = table.getElementsByClassName("no-results-row");
			while (noResultsRows.length > 0) {
				noResultsRows[0].parentNode.removeChild(noResultsRows[0]);
			}

			var found = false;
			for (var i = 0; i < tr.length; i++) {
				var td = tr[i].getElementsByTagName("td")[4];
				if (td) {
					var txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
						found = true;
					} else {
						tr[i].style.display = "none";
					}
				}
			}
			if (!found) {
				var tbody = table.getElementsByTagName("tbody")[0];
				var row = tbody.insertRow(-1);
				row.className = "no-results-row";
				var cell = row.insertCell(0);
				cell.colSpan = 6;
				cell.innerHTML = "Aucun animal ne correspond à votre critère.";
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

	<input type="text" id="search-input" onkeyup="searchAnimal()" placeholder="Rechercher un animal...">
	<button style="position: absolute;right: 1%;" onclick="window.location.href = 'ajoutAnimal.php';">Ajouter un animal</button>
	<table id="animal-table">
		<thead>
		<tr>
			<th>ID</th>
			<th>Race</th>
			<th>Date de naissance</th>
			<th>Sexe</th>
			<th>Pseudo</th>
			<th>Commentaire</th>
			<th>Actions</th>
		</tr>
		</thead>
		<tbody>

<?php

	// create a new instance of DBManager class
	$credentials = new Credentials();
	$db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());
	$animals = $db->getAllAnimaux();

	// Display the animals in an HTML table
	if (!empty($animals)) {
		foreach ($animals as $animal) {
			echo "<tr>";
			echo "<td>" . $animal['id'] . "</td>";
			echo "<td>" . $animal['race'] . "</td>";
			echo "<td>" . $animal['date_naissance'] . "</td>";
			echo "<td>" . $animal['sexe'] . "</td>";
			echo "<td>" . $animal['pseudo'] . "</td>";
			echo "<td>" . $animal['commentaire'] . "</td>";
			echo "<td>";
			echo "<form method='post' action='deleteAnimal.php'>";
			echo "<input type='hidden' name='animal_id' value='" . $animal['id'] . "'>";
			echo "<button type='submit'>Supprimer</button>";
			echo "</form>";
			echo "<form method='get' action='modifAnimal.php'>";
			echo "<input type='hidden' name='animal_id' value=".$animal['id'].">";
			echo "<button type='submit'>Modifier</button>";
			echo "</form>";
			echo "</td>";
			echo "</tr>";
		}
	} else {
		echo "<tr><td colspan='6'>Aucun enregistrement n'a été trouvé</td></tr>";
	}

?>
