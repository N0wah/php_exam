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
$stmt = $conn->prepare("SELECT article.id, article.name, article.price FROM cart JOIN article ON cart.article_id = article.id WHERE cart.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$cartItems = $stmt->get_result();
$stmt->close();

// Calculate total price
$total = 0;
while ($item = $cartItems->fetch_assoc()) {
    $total += $item['price'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_order'])) {
    $billingName = $_POST['billing_name'];
    $billingAddress = $_POST['billing_address'];
    $billingCity = $_POST['billing_city'];
    $billingPostal = $_POST['billing_postal'];

    if ($userBalance >= $total) {
        // Deduct balance
        $newBalance = $userBalance - $total;
        $stmt = $conn->prepare("UPDATE user SET solde = ? WHERE id = ?");
        $stmt->bind_param("di", $newBalance, $userId);
        $stmt->execute();
        $stmt->close();

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();

        // Insert invoice details into the invoice table
        $transactionDate = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO invoice (user_id, transaction_date, amount, invoice_address, invoice_city, invoice_postal) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $userId, $transactionDate, $total, $billingAddress, $billingCity, $billingPostal);
        $stmt->execute();
        $stmt->close();

        echo "Order placed successfully! Invoice generated.";
    } else {
        echo "Insufficient balance to place the order.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
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
<div class="boxvalidation">
    <h2>N/A Company</h2>
    <div class="validationinter">
    <p>Total: <?php echo number_format($total, 2); ?> €</p>
    <form method="post">
        <h3>Information Facture</h3>
        <label for="billing_name">Nom</label>
        <input type="text" id="billing_name" name="billing_name" required><br>
        <label for="billing_address">Adresse</label>
        <input type="text" id="billing_address" name="billing_address" required><br>
        <label for="billing_city">Ville</label>
        <input type="text" id="billing_city" name="billing_city" required><br>
        <label for="billing_postal">Code Postal</label>
        <input type="text" id="billing_postal" name="billing_postal" required><br>
        <button type="submit" name="confirm_order">Confirmer la commande</button>
    </form>
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