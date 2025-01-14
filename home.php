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

// Fetch all articles for sale, ordered by publish_date descending
$sql = "SELECT article.*, user.username FROM article JOIN user ON article.id_author = user.id ORDER BY publish_date DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <form action="account.php" method="get">
        <button type="submit">Go to Account</button>
        <button type="submit" formaction="logout.php">Logout</button>
    </form>

    <h2>Articles for Sale</h2>
    <ul>
        <?php if ($result->num_rows > 0): ?>
            <?php while($article = $result->fetch_assoc()): ?>
                <li>
                    <h3><?php echo htmlspecialchars($article['name']); ?></a></h3>
                    <p><?php echo htmlspecialchars($article['description']); ?></p>
                    <p>Price: <?php echo htmlspecialchars($article['price']); ?></p>
                    <p>Published on: <?php echo htmlspecialchars($article['publish_date']); ?></p>
                    <p>Author: <a href="account.php?user_id=<?php echo htmlspecialchars($article['id_author']); ?>"><?php echo htmlspecialchars($article['username']); ?></p>
                    <?php if ($article['img_link']): ?>
                        <img src="<?php echo htmlspecialchars($article['img_link']); ?>" alt="Article Image" style="max-width: 200px;">
                    <?php endif; ?>
                    <form action="detail.php" method="get">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
                        <button type="submit">View Details</button>
                    </form>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No articles for sale.</p>
        <?php endif; ?>
    </ul>
</body>
</html>