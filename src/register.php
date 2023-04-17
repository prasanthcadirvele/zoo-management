<?php

require_once '../repository/DBManagerDirecteur.php';
require_once '../config/Credentials.php';

use config\Credentials;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $credentials = new Credentials();
    $dbManager = new DBManagerDirecteur($credentials->getServername(),$credentials->getUserDirecteur(), $credentials->getUserDirecteurPassword(), $credentials->getDatabaseName());

    // Get the username and password from the login form
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];
    $user_role = $_POST['user_role'];

    $existsUser = $dbManager->checkPersonnelExists($login_username,$login_password,$user_role);

    if ($existsUser) {
        // The username and password are correct
        session_start();

        // set session variables
        $_SESSION['nom'] = $existsUser["nom"];
        $_SESSION['prenom'] = $existsUser["prenom"];
        $_SESSION['role'] = $existsUser["fonction"];
        $_SESSION['username'] = $existsUser["login"];
        $_SESSION['logged_in'] = true;


        header("location:menu.php");
    } else {

        session_start();

        $_SESSION['message'] ="Nom d'utilisateur et/ou mot de passe et/ou rôle invalide(s)";
        header("location:index.php");

    }

}

?>