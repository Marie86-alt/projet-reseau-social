<?php
    //session_start();
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] == $userId) {
        echo '<a href="#">Profil</a>';
    } else {
        echo '<a href="login.php">Connexion</a>';
    }
?>