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


<div class="boxprofile">
    <h2>Information du profil</h2>
    <div class="inprofile">
    <p>Pseudo: <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email: <?php echo htmlspecialchars($user['mail_adress']); ?></p>
    <p>Solde <?php echo htmlspecialchars($user['solde']); ?></p>

    <?php if ($is_own_account): ?>
        <h2>Éditer votre email</h2>
        <form method="post">
            <label for="email">Nouveau Email</label>
            <input type="email" id="email" name="email" required><br><br>
            <input type="submit" name="update_email" value="Mettre à jour">
        </form>

        <h2>Éditer votre mot de passe</h2>
        <form method="post">
            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" name="update_password" value="Mettre à jour">
        </form>

        <h2>Ajouter du solde</h2>
        <form method="post">
            <label for="amount">Montant</label>
            <input type="number" id="amount" name="amount" required><br><br>
            <input type="submit" name="add_money" value="Ajouter">
        </form>
    <?php endif; ?>

    <h2>Articles Postés</h2>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
            <img src="<?php echo htmlspecialchars($article['img_link']); ?>" alt="Article Image" style="width:50px;height:50px;">
            <?php echo htmlspecialchars($article['name']); ?> - €<?php echo htmlspecialchars($article['price']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($is_own_account): ?>
        <h2>Articles Achetés</h2>
        <ul>
            <?php foreach ($invoices as $invoice): ?>
                <li><?php echo htmlspecialchars($invoice['article_title']); ?></li>
            <?php endforeach; ?>
        </ul>
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