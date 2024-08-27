<?php
require_once 'User.php';

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "classes";

$connexion = new mysqli($servername, $username, $password, $dbname);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

$user = new User($connexion);

$result = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = $user->register(
        $_POST['login'],
        $_POST['password'],
        $_POST['email'],
        $_POST['firstname'],
        $_POST['lastname']
    );
    
    if ($success) {
        // Redirection vers la page de connexion
        header('Location: connexion.php');
        exit();
    } else {
        $result = 'Échec de l\'inscription.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Inscription</title>
</head>
<body>
    <h1>Inscription</h1>
    <p><?php echo $result; ?></p>
    <form method="post">
        <label>Login: <input type="text" name="login" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Firstname: <input type="text" name="firstname" required></label><br>
        <label>Lastname: <input type="text" name="lastname" required></label><br>
        <input type="submit" value="S'inscrire">
    </form>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
$connexion->close();
?>
