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

// Get user ID from GET parameter or session
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
$is_own_account = ($user_id == $_SESSION['user_id']);

// Fetch user information
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Fetch articles posted by the user
$sql = "SELECT * FROM article WHERE id_author = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$articles_result = $stmt->get_result();
$articles = $articles_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch articles purchased by the user (assuming an invoice table)
$sql = "SELECT * FROM invoice WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$invoices_result = $stmt->get_result();
$invoices = $invoices_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Update email
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_email']) && $is_own_account) {
    $new_email = $_POST['email'];
    if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $sql = "UPDATE user SET email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_email, $user_id);
        $stmt->execute();
        $stmt->close();
        echo "Email updated successfully.";
    } else {
        echo "Invalid email format.";
    }
}

// Update password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password']) && $is_own_account) {
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $sql = "UPDATE user SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_password, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "Password updated successfully.";
}

// Add money to balance
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_money']) && $is_own_account) {
    $amount = $_POST['amount'];
    $sql = "UPDATE user SET solde = solde + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $amount, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "Money added successfully.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account</title>
</head>
<body>
    <h2>Account Information</h2>
    <p>Pseudo: <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['mail_adress']); ?></p>
    <p>Balance: <?php echo htmlspecialchars($user['solde']); ?></p>

    <?php if ($is_own_account): ?>
        <h2>Update Email</h2>
        <form method="post">
            <label for="email">New Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <input type="submit" name="update_email" value="Update Email">
        </form>

        <h2>Update Password</h2>
        <form method="post">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="update_password" value="Update Password">
        </form>

        <h2>Add Money to Balance</h2>
        <form method="post">
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" required><br><br>
            <input type="submit" name="add_money" value="Add Money">
        </form>
    <?php endif; ?>

    <h2>Posted Articles</h2>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
            <img src="<?php echo htmlspecialchars($article['img_link']); ?>" alt="Article Image" style="width:50px;height:50px;">
            <?php echo htmlspecialchars($article['name']); ?> - $<?php echo htmlspecialchars($article['price']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($is_own_account): ?>
        <h2>Purchased Articles</h2>
        <ul>
            <?php foreach ($invoices as $invoice): ?>
                <li><?php echo htmlspecialchars($invoice['article_title']); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>
