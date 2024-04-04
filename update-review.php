<?php
require("connect-db.php");
session_start();
if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $text = $_POST['reviewText'];
    $rating = $_POST['rating'];
    $date = date('Y-m-d'); 

    $query = "UPDATE review SET text = :text, rating = :rating, date = :date WHERE user_id = :user_id AND book_id = :book_id";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':text', $text);
        $statement->bindValue(':rating', $rating);
        $statement->bindValue(':user_id', $user_id); 
        $statement->bindValue(':book_id', $book_id); 
        $statement->bindValue(':date', $date);
        $statement->execute();
        $statement->closeCursor();
        header('Location: book-detail.php?book_id=' . $book_id); 
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    exit;
}

echo 'Error: Invalid request';

?>