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

// Initialisation du résultat
$result = '';

// Met à jour les informations de l'utilisateur si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Suppression du compte
        $success = $user->delete(); // Assurez-vous que cette méthode est implémentée dans User.php
        if ($success) {
            session_unset(); // Supprime toutes les variables de session
            session_destroy(); // Détruit la session
            header('Location: index.php');
            exit();
        } else {
            $result = 'Échec de la suppression.';
        }
    } else {
        // Mise à jour des informations
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
}

// Pré-remplissage du formulaire avec les informations actuelles
$currentUserInfo = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/style.css" rel="stylesheet">
    <title>Modifier les informations</title>
</head>
<body>
<?php include'header.php'?>
    <h1>Modifier les informations</h1>
    <p><?php echo htmlspecialchars($result); ?></p>

    <!-- Formulaire de mise à jour des informations -->
    <form method="post">
        <label>Login: <input type="text" name="login" value="<?php echo htmlspecialchars($currentUserInfo['login']); ?>" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($currentUserInfo['email']); ?>" required></label><br>
        <label>Firstname: <input type="text" name="firstname" value="<?php echo htmlspecialchars($currentUserInfo['firstname']); ?>" required></label><br>
        <label>Lastname: <input type="text" name="lastname" value="<?php echo htmlspecialchars($currentUserInfo['lastname']); ?>" required></label><br>
        <input type="submit" value="Mettre à jour">
    </form>

    <!-- Formulaire de suppression du compte -->
    <form method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
        <input type="hidden" name="delete" value="1">
        <input type="submit" value="Supprimer le compte">
    </form>

    <a href="logout.php">Déconnexion</a>
    <?php include'footer.php'?>

</body>
</html>

<?php
$connexion->close();
?>
