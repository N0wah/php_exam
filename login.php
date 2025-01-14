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
<html>
<head>
    <title>Login Page</title>
</head>
<body>
    <h2>Login Page</h2>
    <form action="" method="post">
        <label>Username :</label>
        <input type="text" name="username" required><br>
        <label>Password :</label>
        <input type="password" name="password" required><br>
        <input type="submit" value="Login"><br>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <?php if(isset($error)) { echo $error; } ?>
</body>
</html>