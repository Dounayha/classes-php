<?php

class User {
    private $id;
    public $login;
    private $password;
    public $email;
    public $firstname;
    public $lastname;
    private $connected = false;
    private $mysqli;

    // Constructeur
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    // Méthode pour enregistrer un nouvel utilisateur
    public function register($login, $password, $email, $firstname, $lastname) {
        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Préparation et exécution de la requête
        $stmt = $this->mysqli->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $login, $hashed_password, $email, $firstname, $lastname);
        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            $this->login = $login;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->connected = true;
            return $this->getAllInfos();
        } else {
            return false;
        }
    }

    // Méthode pour connecter un utilisateur
    public function connect($login, $password) {
        $stmt = $this->mysqli->prepare("SELECT id, password, email, firstname, lastname FROM utilisateurs WHERE login = ?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $this->id = $user['id'];
                $this->login = $login;
                $this->password = $user['password'];
                $this->email = $user['email'];
                $this->firstname = $user['firstname'];
                $this->lastname = $user['lastname'];
                $this->connected = true;
                return true;
            }
        }
        return false;
    }

    // Méthode pour déconnecter un utilisateur
    public function disconnect() {
        $this->id = null;
        $this->login = null;
        $this->password = null;
        $this->email = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->connected = false;
    }

    // Méthode pour supprimer un utilisateur
    public function delete() {
        if ($this->connected) {
            $stmt = $this->mysqli->prepare("DELETE FROM utilisateurs WHERE id = ?");
            $stmt->bind_param("i", $this->id);
            if ($stmt->execute()) {
                $this->disconnect();
                return true;
            }
        }
        return false;
    }

    // Méthode pour mettre à jour les informations d'un utilisateur
    public function update($login, $password, $email, $firstname, $lastname) {
        if ($this->connected) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->mysqli->prepare("UPDATE utilisateurs SET login = ?, password = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $login, $hashed_password, $email, $firstname, $lastname, $this->id);
            if ($stmt->execute()) {
                $this->login = $login;
                $this->password = $hashed_password;
                $this->email = $email;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                return true;
            }
        }
        return false;
    }

    // Méthode pour vérifier si l'utilisateur est connecté
    public function isConnected() {
        return $this->connected;
    }

    // Méthode pour récupérer toutes les informations de l'utilisateur
    public function getAllInfos() {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
        ];
    }

    // Méthodes pour récupérer les attributs spécifiques
    public function getLogin() {
        return $this->login;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }
}

?>
