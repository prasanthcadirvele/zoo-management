<?php

session_start();
$formActionURL = "register.php";


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/login.css" rel="stylesheet" type="text/css">
    <title>Login</title>
</head>
    <body>
        <form method="POST" action=<?php echo $formActionURL; ?>>
            <div class="box">
                <div class="form">
                    <h2>Identification</h2>
                    <div class="inputBox">
                        <input type="text" name="login_username" required>
                        <span>Identifiant</span>
                        <i></i>
                    </div>
                    <div class="inputBox">

                        <input type="password" name="login_password" required>
                        <span>Mot de Passe</span>
                        <i></i>
                    </div>

                    <label for="category-select">Catégorie:</label>
                    <select id="category-select" name="user_role">
                        <option value="EMPLOYE">Employé</option>
                        <option value="DIRECTEUR">Directeur</option>
                    </select>

                    <?php
                    if (isset($_SESSION['message'])) {
                        // Display the message to the user
                        echo '<p style="color: red; text-align:center;">' . $_SESSION['message'] . '</p>';

                        // Clear the message from the session
                        unset($_SESSION['message']);
                    }
                    ?>

                    <div class="button-container">
                        <input type="submit" value="Login" class="c">
                    </div>

                </div>
            </div>
        </form>
    </body>
</html>