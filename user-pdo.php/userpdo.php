<?php

class User {
    private $id;
    public $login;
    private $password;
    public $email;
    public $firstname;
    public $lastname;
    private $connected = false;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Pour enregistrer un nouvel utilisateur
    public function register($login, $password, $email, $firstname, $lastname) {
        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Préparation et exécution de la requête
        $stmt = $this->pdo->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$login, $hashed_password, $email, $firstname, $lastname])) {
            $this->id = $this->pdo->lastInsertId();
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

    // Pour connecter un utilisateur
    public function connect($login, $password) {
        $stmt = $this->pdo->prepare("SELECT id, password, email, firstname, lastname FROM utilisateurs WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $this->id = $user['id'];
            $this->login = $login;
            $this->password = $user['password'];
            $this->email = $user['email'];
            $this->firstname = $user['firstname'];
            $this->lastname = $user['lastname'];
            $this->connected = true;
            return true;
        }
        return false;
    }

    // Pour déconnecter un utilisateur
    public function disconnect() {
        $this->id = null;
        $this->login = null;
        $this->password = null;
        $this->email = null;
        $this->firstname = null;
        $this->lastname = null;
        $this->connected = false;
    }

    // Supprimer un utilisateur
    public function delete() {
        if ($this->connected) {
            $stmt = $this->pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
            if ($stmt->execute([$this->id])) {
                $this->disconnect();
                return true;
            }
        }
        return false;
    }

    // Mettre à jour les informations d'un utilisateur
    public function update($login, $password, $email, $firstname, $lastname) {
        if ($this->connected) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("UPDATE utilisateurs SET login = ?, password = ?, email = ?, firstname = ?, lastname = ? WHERE id = ?");
            if ($stmt->execute([$login, $hashed_password, $email, $firstname, $lastname, $this->id])) {
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

    // Pour vérifier si l'utilisateur est connecté
    public function isConnected() {
        return $this->connected;  // Le point-virgule manquant est ajouté ici
    }

    // Pour récupérer toutes les informations de l'utilisateur
    public function getAllInfos() {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
        ];
    }

    // Pour récupérer le login
    public function getLogin() {
        return $this->login;
    }

    // Pour récupérer l'email
    public function getEmail() {
        return $this->email;
    }

    // Pour récupérer le prénom
    public function getFirstname() {
        return $this->firstname;
    }

    // Pour récupérer le nom de famille
    public function getLastname() {
        return $this->lastname;
    }
}

?>
