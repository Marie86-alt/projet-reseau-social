<?php
// Démarrer la session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subscribe'])) {
    // Se connecter à la base de données
    include('connect.php');

    // Récupérer l'id de l'utilisateur connecté
    $connectedId = $_SESSION['connected_id'];
    // Récupérer l'id de l'utilisateur à suivre
    $followedUserId = intval($_POST['followed_user_id']);

    // Ajouter une nouvelle ligne dans la table followers
    $insertQuery = "INSERT INTO followers (following_user_id, followed_user_id) VALUES (?, ?)";
    $stmt = $mysqli->prepare($insertQuery);
    $stmt->bind_param('ii', $connectedId, $followedUserId);

    if ($stmt->execute()) {
        echo "Vous suivez maintenant cet utilisateur.";
    } else {
        echo "Erreur : " . $stmt->error;
    }
    $stmt->close();
}
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
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 */
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
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
if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] != $userId) {
?>
    <form action="subscribe.php" method="post">
        <input type="hidden" name="followed_user_id" value="<?php echo $userId; ?>">
        <button type="submit">S'abonner</button>
    </form>
<?php
}
?>

            </aside>
            <main>
                <?php
                // Etape 4: récupérer tous les messages de l'utilisateur
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias as author_name,
                    COUNT(likes.id) as like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id
                    LEFT JOIN likes      ON likes.post_id  = posts.id
                    WHERE posts.user_id='$userId'
                    GROUP BY posts.id
                    ORDER BY posts.created DESC
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }
                while ($post = $lesInformations->fetch_assoc()){
                    ?>
                    <article>
                      <h3>
                        <time datetime='2020-02-01 11:12:13' ><?php echo $post['created'] ?></time>
                      </h3>
                      <address><?php echo $post['author_name'] ?></address>
                      <div>
                        <p><?php echo $post['content'] ?></p>
                      </div>
                      <footer>
                        <small> ♥ <?php echo $post['like_number'] ?></small>
                        <a href="">#<?php echo $post['taglist'] ?></a>
                      </footer>
                    </article>
                  <?php }?>
            </main>
        </div>
    </body>
</html>