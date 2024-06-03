<?php
// Etape 1: Ouvrir une connexion avec la base de donnée.
$mysqli = new mysqli("localhost:3308", "root", "", "socialnetwork");
//verification
if ($mysqli->connect_errno)
        {
            echo("Échec de la connexion : " . $mysqli->connect_error);
            exit();
        }

?>