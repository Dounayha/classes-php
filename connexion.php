<?php
session_start(); // Démarre la session

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
    if ($user->connect($_POST['login'], $_POST['password'])) {
        // Stocker les informations de l'utilisateur dans la session
        $_SESSION['user'] = $user->getAllInfos();
        // Rediriger vers la page de modification
        header('Location: modifier.php');
        exit();
    } else {
        $result = 'Échec de la connexion.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/style.css" rel="stylesheet">
    <title>Connexion</title>
</head>
<body>
<?php include'header.php'?>
    <h1>Connexion</h1>
    <p><?php echo $result; ?></p>
    <form method="post">
        <label>Login: <input type="text" name="login" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <input type="submit" value="Connecter">
    </form>
    <a href="index.php">Retour à l'accueil</a>
    <?php include'_footer.php'?>

</body>
</html>

<?php
$connexion->close();
?>
