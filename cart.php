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
    <title>Cart Page</title>
</head>
<body>
    <h2>Your Cart</h2>
    <ul>
        <?php if ($cartItems->num_rows > 0): ?>
            <?php while ($item = $cartItems->fetch_assoc()): ?>
                <li>
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                    <?php if ($item['img_link']): ?>
                        <img src="<?php echo htmlspecialchars($item['img_link']); ?>" alt="Article Image" style="max-width: 200px;">
                    <?php endif; ?>
                    <form method="post">
                        <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($item['id']); ?>">
                        <button type="submit" name="remove_item">Remove Item</button>
                    </form>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </ul>

    <?php if ($cartItems->num_rows > 0): ?>
        <form method="post">
            <button type="submit" name="place_order">Place Order</button>
        </form>
    <?php endif; ?>
</body>
</html>
