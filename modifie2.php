<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
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

// Gestion des actions (mise à jour ou suppression)
$result = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        // Met à jour les informations de l'utilisateur
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
    } elseif (isset($_POST['delete'])) {
        // Supprime l'utilisateur
        if ($user->delete()) {
            session_unset(); // Supprime toutes les variables de session
            session_destroy(); // Détruit la session
            header('Location: supprimer.php'); // Redirige vers la page de confirmation de suppression
            exit();
        } else {
            $result = 'Échec de la suppression du compte.';
        }
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
        <input type="submit" name="update" value="Mettre à jour">
    </form>

    <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? .');">
        <input type="submit" name="delete" value="Supprimer le compte" style="color: red;">
    </form>

    <a href="connexion.php">Déconnexion</a>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>

<?php
$connexion->close();
?>
