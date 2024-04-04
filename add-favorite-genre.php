<?php
require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['genre'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$genre = $_POST['genre'];

global $db;
$query = "INSERT INTO user_favorite_genre (user_id, genre) VALUES (:user_id, :genre)";
$statement = $db->prepare($query);
$statement ->execute([':user_id' => $user_id, ':genre' => $genre]);

header('Location: profile.php?favorite=added');
exit;
?>
