<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['book_id'], $_POST['title'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$book_id = $_POST['book_id'];
$title = $_POST['title'];

global $db;
$added_timestamp = date('Y-m-d H:i:s');
$query = "INSERT INTO has (user_id, title, book_id, added_timestamp) VALUES (:user_id, :title, :book_id, :added_timestamp)";
try {
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':book_id', $book_id);
    $statement->bindValue(':added_timestamp', $added_timestamp);
    $statement->execute();
    $statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
header('Location: profile.php?book=added');
exit;
?>
