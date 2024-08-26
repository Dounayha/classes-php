<?php
session_start();
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
    $result = $user->update(
        $_POST['login'],
        $_POST['password'],
        $_POST['email'],
        $_POST['firstname'],
        $_POST['lastname']
    ) ? 'Mise à jour réussie.' : 'Échec de la mise à jour.';
}

$connected = $user->isConnected();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour</title>
</head>
<body>
    <h1>Mise à jour</h1>
    <?php if ($connected): ?>
        <p><?php echo $result; ?></p>
        <form method="post">
            <label>Login: <input type="text" name="login" required></label><br>
            <label>Password: <input type="password" name="password" required></label><br>
            <label>Email: <input type="email" name="email" required></label><br>
            <label>Firstname: <input type="text" name="firstname" required></label><br>
            <label>Lastname: <input type="text" name="lastname" required></label><br>
            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <p>Vous devez être connecté pour mettre à jour vos informations.</p>
    <?php endif; ?>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
$connexion->close();
?>
