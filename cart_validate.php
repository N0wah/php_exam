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
    <title>Order Confirmation</title>
</head>
<body>
    <h2>Order Confirmation</h2>
    <p>Total: $<?php echo number_format($total, 2); ?></p>
    <form method="post">
        <h3>Billing Information</h3>
        <label for="billing_name">Name:</label>
        <input type="text" id="billing_name" name="billing_name" required><br>
        <label for="billing_address">Address:</label>
        <input type="text" id="billing_address" name="billing_address" required><br>
        <label for="billing_city">City:</label>
        <input type="text" id="billing_city" name="billing_city" required><br>
        <label for="billing_postal">Postal Code:</label>
        <input type="text" id="billing_postal" name="billing_postal" required><br>
        <button type="submit" name="confirm_order">Confirm Order</button>
    </form>
</body>
</html>
