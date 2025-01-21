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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $publish_date = date('Y-m-d H:i:s');    
    $user_id = $_SESSION['user_id'];
    $img_link = $_FILES['img_link']['name'] ? $_FILES['img_link']['name'] : null;

    // Handle file upload
    if ($img_link) {
        $target_dir = "uploads/";
        // Check if the uploads directory exists, if not create it
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["img_link"]["name"]);
        if (move_uploaded_file($_FILES["img_link"]["tmp_name"], $target_file)) {
            $img_link = $target_file; // Store the path in the database
        } else {
            echo "Error uploading file.";
            $img_link = null; // Fallback to null if upload fails
        }
    }

    // Insert article into the article table
    $sql = "INSERT INTO article (name, description, price, publish_date, id_author, img_link) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssdsis", $title, $description, $price, $publish_date, $user_id, $img_link);

    if ($stmt->execute()) {
        echo "Article created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
< lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


</head>
<ma>
    <nav>
    <div class="menu">
        <a href="home.php" class="logo">
            <img src="./Logo.png" alt="">
        </a>
        <div class="navigation">
            <ul>
                <li><a href="./home.php">Accueil</a></li>
                <li><a href="./home.php#produit">Produit</a></li>
                <li><a href="./sell.php">Vendre</a></li>
            </ul>
            <div class="profile-section"><div class="profile-page"><span class="material-symbols-outlined">
person
</span> </div>
            <div class="panier"><span class="material-symbols-outlined">
shopping_basket
</span></div></div>
            
        </div>
    </div>
    </nav>
    <main>
    <div class="vente">
    <h2>Vendre un produit</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Nom du produit</label>
        <input type="text" id="title" name="title" required><br><br>
        <label for="description">Description</label>
        <textarea id="description" name="description" maxlength="70" required></textarea><br><br>
        <label for="price">Prix</label>
        <input type="number" id="price" name="price" required><br><br>
        <label for="img_link">Image</label>
        <input type="file" id="img_link" name="img_link"><br><br>
        <input type="submit" value="Create Article">
    </form>
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