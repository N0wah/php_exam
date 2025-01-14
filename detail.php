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
    <title>Detail Page</title>
</head>
<body>
    <h1><?php echo htmlspecialchars($article['name']); ?></h1>
    <p><?php echo htmlspecialchars($article['description']); ?></p>
    <?php if ($article['img_link']): ?>
        <img src="<?php echo htmlspecialchars($article['img_link']); ?>" alt="Article Image" style="max-width: 200px;">
    <?php endif; ?>
    <p>Price: $<?php echo number_format($article['price'], 2); ?></p>

    <form method="post">
        <button type="submit" name="add_to_cart">Add to Cart</button>
    </form>

    <?php if ($article['id_author'] == $_SESSION['user_id']): ?>
        <form action="edit.php" method="post">
            <input type="hidden" name="article_id" value="<?php echo htmlspecialchars($article['id']); ?>">
            <button type="submit">Edit Article</button>
        </form>
    <?php endif; ?>
</body>
</html>