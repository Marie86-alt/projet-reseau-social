<?php
// Supposons que $post_id est l'ID du message
// Assurez-vous que $post est défini et contient l'ID du message
if (isset($post) && isset($post['id'])) {
    $message_id  = $post['id'];
} else {
    echo "La variable \$post ou l'ID du message n'est pas défini.";
    exit;
}
$post = array('id' => $message_id)

// Initialiser la variable like_count
$like_count = 0;

// Assurez-vous que $mysqli est défini et contient la connexion à la base de données
if (isset($mysqli)) {
    // Compter le nombre de likes pour ce message
    $sql = "SELECT COUNT(*) as like_count FROM likes WHERE message_id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $message_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $like_count = $row['like_count'];
            } else {
                echo "Aucun like trouvé pour ce message.";
            }
        } else {
            echo "Une erreur s'est produite lors de l'exécution de la requête.";
        }
        // Fermer la déclaration préparée
        $stmt->close();
    } else {
        echo "Une erreur s'est produite lors de la préparation de la requête.";
    }
} else {
    echo "La variable \$mysqli n'est pas définie.";
}

echo "Ce message a " . $like_count . " likes.";
?>
