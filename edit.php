<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_exam";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the article ID is passed as a POST parameter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['article_id'])) {
    $articleId = $_POST['article_id'];
    $userId = $_SESSION['user_id'];

    // Fetch article data from the database
    $stmt = $conn->prepare("SELECT * FROM article WHERE id = ?");
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();

        // Check if the user is the author or an administrator
        if ($article['id_author'] == $userId or $_SESSION['role'] == 'admin') {
            if (isset($_POST['update_article'])) {
                // Update article
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $img_link = $_POST['img_link'];

                $stmt = $conn->prepare("UPDATE article SET name = ?, description = ?, price = ?, img_link = ? WHERE id = ?");
                $stmt->bind_param("ssdsi", $name, $description, $price, $img_link, $articleId);
                $stmt->execute();
                $stmt->close();

                echo "Article updated successfully.";
            } elseif (isset($_POST['delete_article'])) {
                // Delete article
                $stmt = $conn->prepare("DELETE FROM article WHERE id = ?");
                $stmt->bind_param("i", $articleId);
                $stmt->execute();
                $stmt->close();

                echo "Article deleted successfully.";
            }
        } else {
            echo "You do not have permission to edit this article.";
        }
    } else {
        echo "Article not found.";
    }
} else {
    echo "No article ID provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>N/A Company</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="./logofonblanc.png">

</head>
<body>
    <nav>
    <div class="menu">
        <a href="home.php" class="logo">
            <img src="./Logo.png" alt="">
        </a>
        <div class="navigation">
            <ul>
            <li><a href="#accueil">Accueil</a></li>
                <li><a href="#produit">Produit</a></li>
                <li><a href="./sell.php">Vendre</a></li>
            </ul>
            <div class="profile-section"><div class="profile-page"><a href="account.php"><span class="material-symbols-outlined">
person
</span></a></div>
            <div class="panier"><a href="cart.php"><span class="material-symbols-outlined">
shopping_basket
</span></a></div></div>
            
        </div>
    </div>
    </nav>
    <main>
        <div class="boxboxbox">
    <?php if (isset($article) && ($article['id_author'] == $userId || $_SESSION['role'] == 'admin')): ?>
        <h2>Modifier l'article</h2>
        <div class="edit">
        <form method="post">
            <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']); ?>">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($article['name']); ?>" required><br>
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($article['description']); ?></textarea><br>
            <label for="price">Prix</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($article['price']); ?>" required><br>
            <label for="img_link">Image</label>
            <input type="file" id="img_link" name="img_link" value="<?php echo htmlspecialchars($article['img_link']); ?>"><br>
            <div class="button_edit">
            <button type="submit" name="update_article">Mettre à jour</button>
            <button type="submit" name="delete_article">Supprimer</button>
            </div>
        </form>
        
    <?php endif; ?>
    </div>
    </div>
    </main>
    <footer class="footer">
    <div class="footer-container">
                <div class="footer-section social">
            <h2>Suivez-nous</h2>
            <div class="social-icons">
                <div><a href="#"><i class="fab fa-facebook-f"></i></a>
</div>                <div><a href="#"><i class="fab fa-twitter"></i></a>
   </div>             <div><a href="#"><i class="fab fa-instagram"></i></a>
 </div>               <div><a href="#"><i class="fab fa-linkedin-in"></i></a></div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 NA Company. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>