<?php
// Database connection
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
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, password, role FROM user WHERE username = ?");
    $stmt->bind_param("s", $user);

    // Execute statement
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($pass, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $user;
        $_SESSION['role'] = $role;

        if ($role == 'admin') {
            header("location: admin/admin.php");
        } else {
            header("location: home.php");
        }
    } else {
        $error = "Your Login Name or Password is invalid";
    }

    $stmt->close();
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

    <div class="login">
    <h2>Connexion</h2>
        <div class="boxlogin">
            <form action="" method="post">
                <label>Pseudo</label>
                <input type="text" name="username" required><br>    
                <label>Mot de passe</label>
                <input type="password" name="password" required><br>
                <input type="submit" value="Connexion"><br>
            </form>
        </div>
        <p class="mini_texte">Pas encore inscrit ? <a href="register.php">Cliquez ici !</a></p>
        <?php if(isset($error)) { echo $error; } ?>
    </div>
</body>
</html> 