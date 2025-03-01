<?php
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

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profile_picture = $_FILES['profile_picture']['name'] ? $_FILES['profile_picture']['name'] : null;

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } else {
        // Handle file upload
        if ($profile_picture) {
            $target_dir = "uploads/";
            // Check if the uploads directory exists, if not create it
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file; // Store the path in the database
            } else {
                echo "Error uploading file.";
                $profile_picture = "src/img/Default_Profile_Picture.png"; // Fallback to default if upload fails
            }
        } else {
            $profile_picture = "src/img/Default_Profile_Picture.png";
        }

        $sql = "INSERT INTO user (username, mail_adress, password, profile_picture, solde, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $default_solde = 0;
        $default_role = 'none';
        $stmt->bind_param("ssssss", $pseudo, $email, $password, $profile_picture, $default_solde, $default_role);

        if ($stmt->execute()) {
            // Set session variables
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $pseudo;
            $_SESSION['role'] = $default_role;

            // Redirect to home page
            header("location: home.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
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
    <div class="register">
    <h2>S'incrire</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="pseudo">Pseudo</label>
        <input type="text" id="pseudo" name="pseudo" required><br><br>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" id="profile_picture" name="profile_picture"><br><br>
        <input type="submit" value="S'inscrire">
    </form>
    <p class="mini_texte">inscrit ? <a href="login.php">Cliquez ici !</a></p>
    </div>
    
</body>
</html>