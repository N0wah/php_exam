<?php
// detail.php

// Start session to use session variables
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_exam";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the article ID is passed as a GET parameter
if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    // Fetch article data from the database
    $stmt = $conn->prepare("SELECT id, name, description, price, img_link, id_author FROM article WHERE id = ?");
    $stmt->bind_param("i", $articleId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $article = $result->fetch_assoc();
    } else {
        echo "Article not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "No article ID provided.";
    exit;
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO cart (user_id, article_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $articleId);
    $stmt->execute();
    $stmt->close();

    echo "Article added to cart.";
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
<main>
    <nav>
    <div class="menu">
        <a href="home.php" class="logo">
            <img src="./Logo.png" alt="">
        </a>
        <div class="navigation">
            <ul>
            <li><a href="home.php#accueil">Accueil</a></li>
                <li><a href="home.php#produit">Produit</a></li>
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






    <div class="detail">
        <article>
    <h1><?php echo htmlspecialchars($article['name']); ?></h1>
    <p><?php echo htmlspecialchars($article['description']); ?></p>
    <?php if ($article['img_link']): ?>
        <img src="<?php echo htmlspecialchars($article['img_link']); ?>" alt="Article Image" style="max-width: 200px;">
    <?php endif; ?>
    <p>Prix <?php echo number_format($article['price'], 2); ?> €</p>

    <form method="post">
        <button type="submit" name="add_to_cart">Ajouter au panier</button>
    </form>

    <?php if ($article['id_author'] == $_SESSION['user_id']): ?>
        <form action="edit.php" method="post">
            <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']); ?>">
            <button type="submit">Modifier l'article</button>
        </form>
    <?php endif; ?>
    </article>
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