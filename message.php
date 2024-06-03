<?php
// Démarrer la session
session_start();

// Inclure le fichier de connexion à la base de données
include('connect.php');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $currentDateTime = new DateTime('now');
    $currentDate = $currentDateTime->format('Y-m-d H:i:s');
    $message = $_POST['message'];
    $user_id = $_POST['user_id'];
    

    // Préparer la requête SQL
    $sql = "INSERT INTO posts (content, user_id, created) VALUES (?, ?, '$currentDate')";

    // Instruction de Préparation de la déclaration
    if ($stmt = $mysqli->prepare($sql)) {
        // Lier les variables à la déclaration préparée avec la methode bind_param de l'objet $stmt
        // "si" est une chaine qui specifie des caractère
        // ici S c'est la string et i pour entier
        $stmt->bind_param("si", $message, $user_id);

        // Exécuter la déclaration
        if ($stmt->execute()) {
            // Rediriger l'utilisateur vers son mur
            header("Location: wall.php?user_id=" . $user_id);
            exit();
        } else {
            echo "Quelque chose a mal tourné. Veuillez réessayer plus tard.";
        }
    }

    // Fermer la déclaration
    $stmt->close();
}

// Fermer la connexion
$mysqli->close();
?>
