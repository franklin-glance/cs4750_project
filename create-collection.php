<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['title'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];

global $db;
$query = "INSERT INTO collection (user_id, title) VALUES (:user_id, :title)";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $user_id);
$statement->bindValue(':title', $title);
$statement->execute();
$statement->closeCursor();

header('Location: profile.php?collection=added');
exit;
?>
