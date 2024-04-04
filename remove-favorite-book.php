<?php

require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['book_name'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$book_name = $_POST['book_name'];

global $db;
$query = "DELETE FROM user_favorite_book WHERE user_id = :user_id AND book_name = :book_name";
try {
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':book_name', $book_name);
    $statement->execute();
    $statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}

header('Location: profile.php?favorite=removed');
exit;
?>