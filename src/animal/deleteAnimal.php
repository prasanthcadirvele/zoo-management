<?php



use config\Credentials;
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerEmploye.php';

$errorMessage = null;
$successMessage = null;


    session_start();


// now you can use $_SESSION to access session variables
if (isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true) {
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $credentials = new Credentials();
        $db = new DBManagerEmploye($credentials->getServername(),$credentials->getUserEmploye(), $credentials->getUserEmployePassword(), $credentials->getDatabaseName());

        $db->deleteAnimal($_POST['animal_id']);

        header('Location: listeAnimaux.php');
    }
}