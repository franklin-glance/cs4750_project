<?php
function getReviewsByBookId($bookId) {
    global $db;
    $query = "SELECT * FROM review WHERE book_id = :book_id";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':book_id', $bookId);
        $statement->execute();
        $reviews = $statement->fetchAll();
        $statement->closeCursor();
        return $reviews;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>