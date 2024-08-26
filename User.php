<?php
class User{
private $id;
public $login;
public $email;
public $firstname;
public $lastname;

public function __construct($login, $email, $firstname, $lastname, $id = null) {
    $this->id = $id;
    $this->login = $login;
    $this->email = $email;
    $this->firstname = $firstname;
    $this->lastname = $lastname;
}
//creer un nvx utilisateur
public function create($mysqli) {
    $sql = "INSERT INTO users (login, email, firstname, lastname) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssss", $this->login, $this->email, $this->firstname, $this->lastname);
    if ($stmt->execute()) {
        $this->id = $stmt->insert_id;
        return true;
    } else {
        return false;
    }
}
}

