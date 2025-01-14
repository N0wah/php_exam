<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php_exam";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete post request
if (isset($_GET['delete_post'])) {
    $post_id = intval($_GET['delete_post']);
    $conn->query("DELETE FROM article WHERE id = $post_id");
    header('Location: admin.php');
    exit();
}

// Handle delete user request
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    $conn->query("DELETE FROM user WHERE id = $user_id");
    header('Location: admin.php');
    exit();
}

// Fetch all posts
$posts_result = $conn->query("SELECT * FROM article");

// Fetch all users
$users_result = $conn->query("SELECT * FROM user");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Panel</h1>
    
    <form action="../logout.php" method="post">
        <button type="submit">Logout</button>
    </form>

    <h2>Posts</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image Link</th>
            <th>Actions</th>
        </tr>
        <?php if ($posts_result && $posts_result->num_rows > 0): ?>
            <?php while ($post = $posts_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $post['id']; ?></td>
                    <td><?php echo $post['name']; ?></td>
                    <td><?php echo $post['description']; ?></td>
                    <td><?php echo $post['img_link']; ?></td>
                    <td>
                        <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
                        <a href="admin.php?delete_post=<?php echo $post['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No posts found.</td>
            </tr>
        <?php endif; ?>
    </table>

    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php if ($users_result && $users_result->num_rows > 0): ?>
            <?php while ($user = $users_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['mail_adress']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                        <a href="admin.php?delete_user=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No users found.</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>