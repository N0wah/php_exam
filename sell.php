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
<html>
<head>
    <title>Sell Article</title>
</head>
<body>
    <h2>Sell Article</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        <label for="img_link">Image:</label>
        <input type="file" id="img_link" name="img_link"><br><br>
        <input type="submit" value="Create Article">
    </form>
</body>
</html>
