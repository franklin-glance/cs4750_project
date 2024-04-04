<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['book_name'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$book_name = $_POST['book_name'];

global $db;
$query = "INSERT INTO user_favorite_book (user_id, book_name) VALUES (:user_id, :book_name)";
$stmt = $db->prepare($query);
$stmt->execute([':user_id' => $user_id, ':book_name' => $book_name]);

// Redirect back to profile with success message or handle accordingly
header('Location: profile.php?favorite=added');
exit;
?>
