<?php
session_start();
include('connect.php');

if (!isset($_SESSION['connected_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $followerId = intval($_SESSION['connected_id']);
    $followedId = intval($_POST['user_id']);

    // Vérifier si l'utilisateur est déjà abonné
    $checkSubscriptionSql = "SELECT * FROM followers WHERE following_user_id = $followerId AND followed_user_id = $followedId";
    $result = $mysqli->query($checkSubscriptionSql);

    if ($result->num_rows === 0) {
        // Ajouter l'abonnement
        $subscribeSql = "INSERT INTO followers (following_user_id, followed_user_id) VALUES ($followerId, $followedId)";
        if ($mysqli->query($subscribeSql)) {
            echo "Abonnement réussi!";
        } else {
            echo "Erreur lors de l'abonnement : " . $mysqli->error;
        }
    } else {
        echo "Vous êtes déjà abonné à cet utilisateur.";
    }
}
header('Location: wall.php?user_id=' . $followedId);
exit();
?>






















