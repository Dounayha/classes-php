<?php
session_start(); // Démarre la session

// Déconnexion de l'utilisateur
if (isset($_SESSION['user'])) {
    session_unset(); // Supprime toutes les variables de session
    session_destroy(); // Détruit la session
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./assets/style.css" rel="stylesheet">

    <title>Déconnexion</title>
</head>
<body>
    <h1>Déconnexion réussie</h1>
    <p>Vous avez été déconnecté .</p>
    <form action="connexion.php" method="get">
        <input type="submit" value="Revenir à la page de connexion">
    </form>
    <form action="index.php" method="get">
        <input type="submit" value="Retour à l'accueil">
    </form>
</body>
</html>
