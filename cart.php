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

// Fetch user balance
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT solde FROM user WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$userBalance = $user['solde'];
$stmt->close();

// Fetch cart items
$stmt = $conn->prepare("SELECT article.id, article.name, article.price, article.img_link FROM cart JOIN article ON cart.article_id = article.id WHERE cart.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();
$stmt->close();

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove_item'])) {
        $articleId = $_POST['article_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND article_id = ?");
        $stmt->bind_param("ii", $userId, $articleId);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['place_order'])) {
        header("location: cart_validate.php");
        exit;
    }

    // Refresh cart items after update
    $stmt = $conn->prepare("SELECT article.id, article.name, article.price, article.img_link FROM cart JOIN article ON cart.article_id = article.id WHERE cart.user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $cartItems = $stmt->get_result();
    $stmt->close();
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
    <div class="panierb">
    <h2>Panier</h2>
    <div class="boxpanier">
    <ul>
        <?php if ($cartItems->num_rows > 0): ?>
            <?php while ($item = $cartItems->fetch_assoc()): ?>
                <li>
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Prix <?php echo number_format($item['price'], 2); ?> €</p>
                    <?php if ($item['img_link']): ?>
                        <img src="<?php echo htmlspecialchars($item['img_link']); ?>" alt="Article Image" style="max-width: 200px;">
                    <?php endif; ?>
                    <form method="post">
                        <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                        <button type="submit" name="remove_item">Enlever l'article</button>
                    </form>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Votre panier est vide 😔</p>
        <?php endif; ?>
    </ul>

    <?php if ($cartItems->num_rows > 0): ?>
        <form method="post">
            <button type="submit" name="place_order">Commander</button>
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