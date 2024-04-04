<?php 
require("connect-db.php");
require("user-db.php");
session_start();

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user = getUserByUserId($user_id);

require("header.php"); 
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Welcome to The Ultimate Book Collection</title>
</head>
<body>
    <div class="container">
        <h1>Welcome to The Ultimate Book Collection Platform</h1>
        <p>Hello, <?php echo htmlspecialchars($user['user_id']); ?>! Explore the vast universe of books, share your reviews, and curate your personal book collection.</p>
        <nav>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="book-list.php">Book List</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>

<?php include('footer.html'); ?>
