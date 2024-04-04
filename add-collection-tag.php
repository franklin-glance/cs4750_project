<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['tag'], $_POST['title'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$tag = $_POST['tag'];
$title = $_POST['title'];

global $db;
$query = "INSERT INTO collection_tags (user_id, title, tag) VALUES (:user_id, :title, :tag)";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $user_id);
$statement->bindValue(':title', $title);
$statement->bindValue(':tag', $tag);
$statement->execute();
$statement->closeCursor();

header('Location: profile.php?tag=added');
exit;
?>
