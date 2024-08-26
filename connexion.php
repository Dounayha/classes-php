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
    $result = $user->connect($_POST['login'], $_POST['password']) ? 'Connexion réussie.' : 'Échec de la connexion.';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <h1>Connexion</h1>
    <p><?php echo $result; ?></p>
    <form method="post">
        <label>Login: <input type="text" name="login" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <input type="submit" value="Connect">
    </form>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
$connexion->close();
?>
