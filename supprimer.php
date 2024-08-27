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
    if (isset($_POST['delete'])) {
        // Suppression du compte
        $success = $user->delete();
        if ($success) {
            session_unset();
            session_destroy();
            header('Location: index.php');
            exit();
        } else {
            $result = 'Échec de la suppression.';
        }
    }
}

$connected = $user->isConnected();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/style.css" rel="stylesheet">
    <title>Suppression</title>
</head>
<body>
<?php include'header.php'?>
    <h1>Suppression</h1>
    <?php if ($connected): ?>
        <p><?php echo htmlspecialchars($result); ?></p>
        <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
            <input type="hidden" name="delete" value="1">
            <input type="submit" value="Supprimer le compte">
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
