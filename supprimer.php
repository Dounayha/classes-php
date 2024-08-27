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
    $result = $user->delete() ? 'Suppression réussie.' : 'Échec de la suppression.';
}

$connected = $user->isConnected();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

    <title>Suppression</title>
</head>
<body>
    <h1>Suppression</h1>
    <?php if ($connected): ?>
        <p><?php echo $result; ?></p>
        <form method="post">
            <input type="hidden" name="action" value="delete">
            <input type="submit" value="Delete">
        </form>
    <?php else: ?>
        <p>Vous devez être connecté pour supprimer votre compte.</p>
    <?php endif; ?>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
$connexion->close();
?>
