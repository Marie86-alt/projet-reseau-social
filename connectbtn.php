<?php
if (!isset($_SESSION['connected_id'])) {
    // L'utilisateur est connecté, afficher le lien vers le profil
    echo '<a>Profil</a>';
} else {
    // L'utilisateur n'est pas connecté, afficher le lien vers la page de connexion
    echo '<a href="login.php">Connexion</a>';
}
?>
