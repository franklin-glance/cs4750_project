<?php
require("connect-db.php");
session_start();

if(!isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $db;
    $user_id = $_SESSION['user_id'];
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];
    $text = $_POST['reviewText'];
    $date = date('Y-m-d'); // Current date

    $query = "SELECT * FROM review WHERE user_id = :user_id AND book_id = :book_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':book_id', $book_id);
    $statement->execute();
    $review = $statement->fetch();
    $statement->closeCursor();

    if ($review) {
        header("Location: book-detail.php?book_id=$book_id");
        exit;
    }


    $query = "INSERT INTO review (user_id, book_id, rating, text, date) VALUES (:user_id, :book_id, :rating, :text, :date)";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':book_id', $book_id);
        $statement->bindValue(':rating', $rating);
        $statement->bindValue(':text', $text);
        $statement->bindValue(':date', $date);
        $statement->execute();
        $statement->closeCursor();
        header("Location: book-detail.php?book_id=$book_id");
    } catch (PDOException $e) {
        echo "Error: $e";
    }
} else {
    header("Location: book-list.php");
    exit;
}
?>
