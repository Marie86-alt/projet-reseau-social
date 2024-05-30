<?php
// Démarrer la session
session_start();
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <nav id="menu">
                <a href="news.php">Actualités</a>
                <a href="wall.php?user_id=5">Mur</a>
                <a href="feed.php?user_id=5">Flux</a>
                <a href="tags.php?tag_id=1">Mots-clés</a>
            </nav>
            <nav id="user">
                <?php
                include('connectbtn.php');
                ?>  
                <ul>
                    <li><a href="settings.php?user_id=5">Paramètres</a></li>
                    <li><a href="followers.php?user_id=5">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=5">Mes abonnements</a></li>
                </ul>
            </nav>
        </header>
        <div id="wrapper">
            <?php
            // Etape 1: Le mur concerne un utilisateur en particulier
            // La première étape est donc de trouver quel est l'id de l'utilisateur
            $userId = intval($_GET['user_id']);
            ?>
            <?php
            // Etape 2: se connecter à la base de données
            include('connect.php');
            ?>

            <aside>
                <?php
                // Etape 3: récupérer le nom de l'utilisateur
                $laQuestionEnSql = "SELECT * FROM users WHERE id = ?";
                $stmt = $mysqli->prepare($laQuestionEnSql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisateur"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les messages de l'utilisateur : <?php echo htmlspecialchars($user['alias']); ?>
                        (n° <?php echo $userId; ?>)
                    </p>
                </section>
                <?php
                // Ajouter le formulaire d'abonnement si l'utilisateur n'est pas sur son propre mur
                
                ?>
                <form action="subscriptions.php" method="post">
                    <input type="hidden" name="followed_user_id" value="<?php echo $userId; ?>">
                    <button type="submit">S'abonner</button>
                </form>
                <?php if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] != $userId) {
                }
                ?>

                <?php
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                  }
                  
                  // la requête SQL
                  $sql = "INSERT INTO MyTable (colonne1, colonne2)
                  VALUES ('email, 'valeur2')";
                  
                  if ($conn->query($sql) === TRUE) {
                    echo "Nouvel enregistrement créé avec succès";
                  } else {
                    echo "Erreur: " . $sql . "<br>" . $conn->error;
                  }
                  
                  $conn->close();
                  ?>

               
            </aside>
            <main>
                <?php
                // Etape 4: récupérer tous les messages de l'utilisateur
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias AS author_name, 
                    COUNT(likes.id) AS like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist 
                    FROM posts
                    JOIN users ON users.id = posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags ON posts_tags.tag_id = tags.id 
                    LEFT JOIN likes ON likes.post_id = posts.id 
                    WHERE posts.user_id = ?
                    GROUP BY posts.id
                    ORDER BY posts.created DESC";
                $stmt = $mysqli->prepare($laQuestionEnSql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if (!$result) {
                    echo("Échec de la requête : " . $mysqli->error);
                }

                while ($post = $result->fetch_assoc()) {
                ?>
                    <article>
                        <h3>
                            <time datetime='<?php echo $post['created']; ?>'><?php echo $post['created']; ?></time>
                        </h3>
                        <address><?php echo htmlspecialchars($post['author_name']); ?></address>
                        <div>
                            <p><?php echo htmlspecialchars($post['content']); ?></p>
                        </div>
                        <footer>
                            <small>♥ <?php echo $post['like_number']; ?></small>
                            <a href="tags.php?tag_id=<?php echo htmlspecialchars($post['taglist']); ?>">#<?php echo htmlspecialchars($post['taglist']); ?></a>
                        </footer>
                    </article>
                <?php } ?>
            </main>
        </div>
    </body>
</html>
