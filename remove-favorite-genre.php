<?php

require("connect-db.php");
session_start();

if (!isset($_SESSION['user_id'], $_POST['genre'])) {
    exit('Invalid request');
}

$user_id = $_SESSION['user_id'];
$genre = $_POST['genre'];

global $db;
$query = "DELETE FROM user_favorite_genre WHERE user_id = :user_id AND genre = :genre";
try {
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':genre', $genre);
    $statement->execute();
    $statement->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}

header('Location: profile.php?favorite=removed');
exit;
?>