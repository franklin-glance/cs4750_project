<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['book_id'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];

global $db;
$query = "DELETE FROM review WHERE user_id = :user_id AND book_id = :book_id";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $user_id);
$statement->bindValue(':book_id', $book_id);
$statement->execute();
$statement->closeCursor();

header('Location: book-detail.php?book_id=' . $book_id);
exit;

?>