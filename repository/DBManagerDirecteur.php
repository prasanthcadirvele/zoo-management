<?php

require_once 'DBManager.php';
class DBManagerDirecteur extends DBManager {
    // Function to retrieve all personnel
    function getAllPersonnels() {
        $conn = $this->getConnection();
        $sql = "SELECT * FROM personnels";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            $personnels = array();
            while($row = $result->fetch_assoc()) {
                $personnels[] = $row;
            }
        } else {
            $personnels = null;
        }

        $conn->close();

        return $personnels;
    }

// Function to retrieve a personnel by ID
    public function getPersonnelById($id) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM personnels WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $personnel = $result->fetch_assoc();
        } else {
            $personnel = null;
        }

        $stmt->close();
        $conn->close();

        return $personnel;
    }


// Function to add a personnel
    public function addPersonnel($nom, $prenom, $date_naissance, $sexe, $login, $mdp, $fonction, $salaire) {
        $conn = $this->getConnection();
        $sql = "INSERT INTO personnels (nom, prenom, date_naissance, sexe, login, mdp, fonction, salaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssd", $nom, $prenom, $date_naissance, $sexe, $login, $mdp, $fonction, $salaire);
        $stmt->execute();
        $personnel_id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
        return $personnel_id;
    }


// Function to delete a personnel by ID
    public function deletePersonnel($id) {
        $conn = $this->getConnection();
        $sql = "DELETE FROM personnels WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $success;
    }



    function checkPersonnelExists($login, $mdp, $role) {
        // Get database connection
        $conn = $this->getConnection();

        // Prepare SQL statement
        $stmt = $conn->prepare("SELECT * FROM personnels WHERE login = ? AND mdp = ? AND fonction = ?");
        $stmt->bind_param("sss", $login, $mdp, $role);

        // Execute SQL statement
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Check if personnel exists
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getPersonnelByLogin($login) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM personnels WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $conn->close();

        if ($result->num_rows === 0) {
            return null;
        }

        $personnel = $result->fetch_assoc();
        return $personnel;
    }

    public function updatePersonnel($id, $nom, $prenom, $date_naissance, $sexe, $login, $fonction, $salaire) {
        $conn = $this->getConnection();
        $sql = "UPDATE personnels SET nom=?, prenom=?, date_naissance=?, sexe=?, login=?, fonction=?, salaire=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssdi", $nom, $prenom, $date_naissance, $sexe, $login, $fonction, $salaire, $id);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $success;
    }

}