<?php
// Démarrer la session
session_start();
<<<<<<< HEAD
    ?>
=======
if (!(isset($_SESSION['connected_id']))) {
    header("Location: login.php");
}
?>
>>>>>>> 910f2c3cc3f1ed7ab88f040f1c474edd128cdc25

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Mur</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header>
        <img src="resoc.jpg" alt="Logo de notre réseau social" />
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="wall.php?user_id=5">Mur</a>
            <a href="feed.php?user_id=5">Flux</a>
            <a href="tags.php?tag_id=1">Mots-clés</a>
        </nav>
        <nav id="user">
            <?php
            include ('connectbtn.php');
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
        include ('connect.php');
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
            <img src="user.jpg" alt="Portrait de l'utilisateur" />
            <section>
                <h3>Présentation</h3>


                <p>Sur cette page vous trouverez tous les message de l'utilisatrice :
                    <?php
                    echo $user['alias']
                        ?>
                    (n° <?php echo $userId ?>)
                </p>
            </section>
<<<<<<< HEAD
            <section>
            <?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']))
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        $postLike = intval($_POST['post_id']);
 // Afficher la valeur de post_id pour le débogage
 //echo "post_id reçu: " . htmlspecialchars($postLike) . "<br>";

                        // Petite sécuritén - pour éviter les injection sql :
                        $postLike = $mysqli->real_escape_string($postLike);

                        // Construction de la requete
                        //$lInstructionSql = "INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES (NULL, ?, ?)";
                              // Vérifier si le post_id existe dans la table posts_tags
                    $verifPostSql = "SELECT id FROM posts WHERE id = ?";
                    $stmt = $mysqli->prepare($verifPostSql);
                    $stmt->bind_param("i", $postLike);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        // Construction de la requête
                        $lInstructionSql = "INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES (NULL, ?, ?)";
                        $stmt = $mysqli->prepare($lInstructionSql);
                        $stmt->bind_param("ii", $_SESSION["connected_id"], $postLike);
                        $ok = $stmt->execute();
                        if (!$ok) {
                            echo "Impossible d'ajouter un like: " . $mysqli->error;
                        } else {
                            echo "like posté";
                        }
                    } else {
                        echo "Le post que vous essayez de liker n'existe pas.";
                    }
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                }
                    
                ?>
            </section>
            
            
=======

>>>>>>> 910f2c3cc3f1ed7ab88f040f1c474edd128cdc25
            <section class="message">

                <?php
                if (isset($_SESSION['connected_id'])) {
                    $connectedUser = $_SESSION['connected_id'];
                    ?>
                    <form action="message.php" method="post">
                        <label for="message"> Nouveau message :</label>
                        <textarea id="message" name="message" size="20" maxlength="30"></textarea>
                        <input type="hidden" name="user_id" value="<?php echo $connectedUser; ?>">
                        <input type="submit" value="Publier">
                    </form>
                    <?php
                }
                ?>

            </section>

            <section class="follow">
                <?php
<<<<<<< HEAD
                    $follower_user_id = $userId;
                    $following_user_id = $connectedUser;
                    // une session sert a authentifier un utilisateur
=======
                $follower_user_id = $connectedUser;
                $following_user_id = $userId;
>>>>>>> 910f2c3cc3f1ed7ab88f040f1c474edd128cdc25
                if (isset($_SESSION['connected_id'])) {
                    $connectedUser = $_SESSION['connected_id'];
                    if ($connectedUser != $userId) {
                        if (isset($_POST['subscribe'])) {
                            $sql = "INSERT INTO followers (followed_user_id, following_user_id) VALUES (?, ?)";
                            $stmt = $mysqli->prepare($sql);
                            $stmt->bind_param("ii", $follower_user_id, $following_user_id);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
                        }

                        ?>
                        <form method="post">
                            <input type="hidden" name="follower_user_id" value="<?php echo $follower_user_id; ?>">
                            <input type="hidden" name="following_user_id" value="<?php echo $following_user_id; ?>">
                            <input type='submit' name="subscribe" value="s'abonner">
                        </form>
                        <?php
                        if(isset($_POST['suscribe'])){
                            echo '<p><br /> vous êtes abonné à' . $userId . '!</p>';
                        }
                    }

                }
                ?>

            </section>

        </aside>
        <main>
            <?php
            // Etape 4: récupérer tous les messages de l'utilisateur
            $laQuestionEnSql = "
                    SELECT posts.id as post_id, posts.content, posts.created, users.alias as author_name,
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
            if (!$lesInformations) {
                echo ("Échec de la requete : " . $mysqli->error);
            }
            while ($post = $lesInformations->fetch_assoc()) {
                ?>
                <article>
                    <h3>
                        <time datetime='2020-02-01 11:12:13'><?php echo $post['created'] ?></time>
                    </h3>
                    <address><?php echo $post['author_name'] ?></address>
                    <div>
                        <p><?php echo $post['content'] ?></p>
                    </div>
                    <footer>
                    <small>
                        <form action="" method="post">
                             <input type='hidden' name='post_id' value='<?php echo $post['post_id'] ?>'>
                             <input type="submit" value="like">
                                ♥ <?php echo $post['like_number'] ?>
                             </form>
                            </small>
                            <?php 
                            if (!empty($post['taglist'])) {
                                $tags = explode(",", $post['taglist']);
                                foreach ($tags as $tag) {
                                    echo "<a href='tags.php?tag_id=" . htmlspecialchars($tag) . "'>" . htmlspecialchars($tag) . "</a> &nbsp;";
                                }
                            }
                            ?>
                        
                        
                        <a href="">#<?php echo $post['taglist'] ?></a>
         
                    </footer>
                </article>
            <?php } ?>
        </main>
    </div>
</body>

</html>