<?php
function getTotalBooks() {
    global $db;
    $sql = "SELECT COUNT(*) FROM book";
    $statement = $db->query($sql);
    return (int) $statement->fetchColumn();
}

function getAuthorsByBookId($bookId) {
    global $db;
    $bookId = (int) $bookId;
    $query = "SELECT DISTINCT author FROM book_author WHERE book_id = :book_id";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':book_id', $bookId);
        $statement->execute();
        $authors = $statement->fetchAll();
        $statement->closeCursor();
        return $authors;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function getGenresByBookId($bookId) {
    global $db;
    $bookId = (int) $bookId;
    $query = "SELECT DISTINCT genre FROM book_genre WHERE book_id = :book_id"; 
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':book_id', $bookId);
        $statement->execute();
        $genres = $statement->fetchAll();
        $statement->closeCursor();
        return $genres;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function getBookById($bookId) {
    global $db;
    $bookQuery = "SELECT * FROM book WHERE book_id = :book_id";
    try {
        $statement = $db->prepare($bookQuery);
        $statement->bindValue(':book_id', $bookId);
        $statement->execute();
        $book = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $book;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function getBooksByTitle($page, $itemsPerPage, $searchTerm = '', $searchColumn = 'title') {
    global $db;
    $offset = ($page - 1) * $itemsPerPage; 
    if ($offset < 0) {
        $offset = 0;
    }
    $query = "SELECT * FROM book ";
    if (!empty($searchTerm)) {
        $query .= "WHERE $searchColumn LIKE :searchTerm ";
    }
    $query .= "ORDER BY title LIMIT $itemsPerPage OFFSET $offset";
    try {
        $statement = $db->prepare($query);
        if (!empty($searchTerm)) {
            $likeSearchTerm = '%' . $searchTerm . '%';
            $statement->bindParam(':searchTerm', $likeSearchTerm, PDO::PARAM_STR);
        }
        $statement->execute();
        $books = $statement->fetchAll();
        $statement->closeCursor();
        foreach ($books as $key => $book) {
            $authors = getAuthorsByBookId($book['book_id']);
            $genres = getGenresByBookId($book['book_id']);
            $books[$key]['authors'] = $authors;
            $books[$key]['genres'] = $genres;
        }


        return $books;
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function getReviewByUserIdAndBookId($user_id, $book_id) {
    global $db;
    $query = "SELECT * FROM review WHERE user_id = :user_id AND book_id = :book_id";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':book_id', $book_id);
        $statement->execute();
        $review = $statement->fetch();
        $statement->closeCursor();
        return $review;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>
