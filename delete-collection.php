<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['title'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];

global $db;
$query = "DELETE FROM collection WHERE user_id = :user_id AND title = :title";
$statement = $db->prepare($query);
$statement->bindValue(':user_id', $user_id);
$statement->bindValue(':title', $title);
$statement->execute();
$statement->closeCursor();

header('Location: profile.php?collection=removed');
exit;
?>