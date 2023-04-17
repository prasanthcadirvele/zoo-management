<?php

require_once 'DBManager.php';
class DBManagerEmploye extends DBManager{

    public function addEspece($race, $nourriture, $duree_vie, $aquatique) {
        // Get database connection
        $conn = $this->getConnection();

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO especes (race, nourriture, duree_vie, aquatique) VALUES (?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("ssii", $race, $nourriture, $duree_vie, $aquatique);

        // Execute statement
        $result = $stmt->execute();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Check if insertion was successful
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function addAnimal($race_id, $date_naissance, $sexe, $pseudo, $commentaire) {
        // Get database connection
        $conn = $this->getConnection();

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO animaux (race_id, date_naissance, sexe, pseudo, commentaire) VALUES (?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("issss", $race_id, $date_naissance, $sexe, $pseudo, $commentaire);

        // Execute statement
        $result = $stmt->execute();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Check if execution was successful
        if ($result === false) {
            return false;
        } else {
            return true;
        }
    }

    public function updateAnimal($id, $race_id, $date_naissance, $sexe, $pseudo, $commentaire) {
        // Get connection
        $conn = $this->getConnection();

        // Prepare SQL statement
        $stmt = $conn->prepare("UPDATE animaux SET race_id = ?, date_naissance = ?, sexe = ?, pseudo = ?, commentaire = ? WHERE id = ?");

        // Bind parameters
        $stmt->bind_param("issssi", $race_id, $date_naissance, $sexe, $pseudo, $commentaire, $id);

        // Execute statement
        $success = $stmt->execute();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Check execution and return true or false
        if ($success) {
            return true;
        } else {
            return false;
        }
    }

    public function getAnimalById($animalId)
    {
        // Get connection
        $conn = $this->getConnection();

        // Prepare query
        $query = "SELECT * FROM animaux WHERE id = ?";

        // Prepare statement and bind parameters
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $animalId);

        // Execute statement and get result
        $stmt->execute();
        $result = $stmt->get_result();

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Return animal details as associative array
        return $result->fetch_assoc();

    }

    public function getAllRaces()
    {
        // Get connection
        $conn = $this->getConnection();

        // Prepare query
        $query = "SELECT * FROM especes";

        // Execute query and get result
        $result = $conn->query($query);

        // Close connection
        $conn->close();

        // Return races as associative array
        $races = array();
        while ($row = $result->fetch_assoc()) {
            $races[] = $row;
        }
        return $races;
    }

    public function getAllAnimaux(){
        $conn = $this->getConnection();
        $query = "SELECT a.id, e.race, a.date_naissance, a.sexe, a.pseudo, a.commentaire FROM animaux a INNER JOIN especes e ON a.race_id = e.id";
        $result = $conn->query($query);

        // Close connection
        $conn->close();

        // Return races as associative array
        $animaux = array();
        while ($row = $result->fetch_assoc()) {
            $animaux[] = $row;
        }
        return $animaux;

    }

    public function deleteAnimal($animal_id) {
        $conn = $this->getConnection();
        $query = "DELETE FROM animaux WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $animal_id);

        // Execute statement and get result
        $stmt->execute();
        $conn->close();
        return $stmt->get_result();
    }

    function getAnimalByPseudo($pseudo) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare('SELECT * FROM animaux WHERE pseudo = ?');
        $stmt->bind_param('s', $pseudo);
        $stmt->execute();
        $result = $stmt->get_result();
        $animal = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $animal ? $animal : false;
    }

    function getAllLocAnimaux() {
    $conn = $this->getConnection();
    $sql = "SELECT loc_animaux.id, animaux.pseudo, loc_animaux.date_arrivee, loc_animaux.date_sortie FROM loc_animaux INNER JOIN animaux ON loc_animaux.animal_id = animaux.id";
    $result = $conn->query($sql);
    $rows = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
    $conn->close();
    return $rows;
}

    public function getLocAnimalByAnimalId($animalId)
    {
        // Get database connection
        $conn = $this->getConnection();

        // Prepare and execute query
        $stmt = $conn->prepare('SELECT * FROM loc_animaux WHERE animal_id   = ?');
        $stmt->bind_param('i', $animalId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Return false if no rows were found
        if ($result->num_rows == 0) {
            return false;
        }

        $stmt->close();
        $conn->close();

        // Return the locanimal data as an associative array
        return $result->fetch_assoc();
    }

    public function getLocAnimalById($id)
    {
        // Get database connection
        $conn = $this->getConnection();

        // Prepare and execute query
        $stmt = $conn->prepare('SELECT * FROM loc_animaux WHERE id   = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Return false if no rows were found
        if ($result->num_rows == 0) {
            return false;
        }

        $stmt->close();
        $conn->close();

        // Return the locanimal data as an associative array
        return $result->fetch_assoc();
    }

    public function addLocAnimal($animal_id, $date_arrivee, $date_sortie)
    {
        // Get database connection
        $conn = $this->getConnection();

        $sql = "INSERT INTO loc_animaux (animal_id, date_arrivee, date_sortie) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $animal_id, $date_arrivee, $date_sortie);
        $result = $stmt->execute();
        $conn->close();
        return $result;
    }

    function deleteLocAnimal($loc_animal_id) {
        // Get database connection
        $conn = $this->getConnection();

        $stmt = $conn->prepare("DELETE FROM loc_animaux WHERE id = ?");
        $stmt->bind_param("i", $loc_animal_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $result;
    }

    public function updateLocAnimal($id, $animal_id, $date_arrivee, $date_sortie) {
        $conn = $this->getConnection();

        // Prepare statement
        $stmt = $conn->prepare("UPDATE loc_animaux SET animal_id= ?, date_arrivee = ?, date_sortie = ? WHERE id = ?");
        $stmt->bind_param("issi", $animal_id, $date_arrivee, $date_sortie, $id);

        $result = $stmt->execute();

        $stmt->close();
        $conn->close();

        // Execute statement
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}