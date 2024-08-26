<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "classes";

$connexion = new mysqli($servername, $username, $password, $dbname);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

// Création de l'objet User
require_once 'User.php';
$user = new User($connexion);

// Met à jour les informations de l'utilisateur si le formulaire est soumis
$result = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = $user->update(
        $_POST['login'],
        $_POST['password'],
        $_POST['email'],
        $_POST['firstname'],
        $_POST['lastname']
    );
    
    if ($success) {
        $_SESSION['user'] = $user->getAllInfos(); // Met à jour les informations dans la session
        $result = 'Mise à jour réussie.';
    } else {
        $result = 'Échec de la mise à jour.';
    }
}

// Pré-remplissage du formulaire avec les informations actuelles
$currentUserInfo = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les informations</title>
</head>
<body>
    <h1>Modifier les informations</h1>
    <p><?php echo $result; ?></p>
    <form method="post">
        <label>Login: <input type="text" name="login" value="<?php echo htmlspecialchars($currentUserInfo['login']); ?>" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($currentUserInfo['email']); ?>" required></label><br>
        <label>Firstname: <input type="text" name="firstname" value="<?php echo htmlspecialchars($currentUserInfo['firstname']); ?>" required></label><br>
        <label>Lastname: <input type="text" name="lastname" value="<?php echo htmlspecialchars($currentUserInfo['lastname']); ?>" required></label><br>
        <input type="submit" value="Mettre à jour">
    </form>
    <a href="logout.php">Déconnexion</a>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
$connexion->close();
?>
