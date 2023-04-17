<?php


use config\Credentials;
require_once '../../config/Credentials.php';
require_once '../../repository/DBManagerDirecteur.php';

session_start();


if (isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true || $_SESSION['role'] !== 'DIRECTEUR') {
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $credentials = new Credentials();
        $db = new DBManagerDirecteur($credentials->getServername(),$credentials->getUserDirecteur(), $credentials->getUserDirecteurPassword(), $credentials->getDatabaseName());

        $db->deletePersonnel($_POST['personnel_id']);

        header('Location: listePersonnel.php');
    }
}