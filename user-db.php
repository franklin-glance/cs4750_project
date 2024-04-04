<?php
// Create User
function createUser($user_id, $password) {
    global $db;
    $query = "INSERT INTO user (user_id,password) VALUES (:user_id, :password)";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        // print out the error 
        echo $e->getMessage();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}


// get user by user_id
function getUserByUserId($user_id) {
    global $db;
    $query = "SELECT * FROM user WHERE user_id = :user_id";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $user;
    } catch (PDOException $e) {
        $e->getMessage();
    }
}


function getUserFavoriteBooks($user_id) {
    global $db; 
    
    $query = "SELECT book_name FROM user_favorite_book WHERE user_id = :user_id ORDER BY book_name ASC";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $books = $statement->fetchAll();
        $statement->closeCursor();
        return $books;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getUserFavoriteGenres($user_id) {
    global $db;
    $query = "SELECT genre FROM user_favorite_genre WHERE user_id = :user_id ORDER BY genre ASC";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $genres = $statement->fetchAll();
        $statement->closeCursor();
        return $genres;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getCollectionTags($user_id, $title){
    global $db;
    $query = "SELECT tag FROM collection_tags WHERE user_id = :user_id AND title = :title";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':title', $title);
        $statement->execute();
        $tags = $statement->fetchAll();
        $statement->closeCursor();
        $result = [];
        foreach ($tags as $tag) {
            $result[] = $tag['tag'];
        }
        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getCollectionBooks($user_id, $title) {
    global $db;
    $query = "SELECT book_id FROM has WHERE user_id = :user_id AND title = :title";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->bindValue(':title', $title);
        $statement->execute();
        $books = $statement->fetchAll();
        $statement->closeCursor();
        return $books;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } 
}
function getBookById2($book_id) {
    global $db;
    $query = "SELECT * FROM book WHERE book_id = :book_id";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':book_id', $book_id);
        $statement->execute();
        $book = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $book;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getUserCollections($user_id){
    global $db;
    $query = "SELECT title FROM collection WHERE user_id = :user_id ORDER BY title ASC";
    try {
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $user_id);
        $statement->execute();
        $collections = $statement->fetchAll();

        $result = [];
        if ($collections) {
            foreach ($collections as $collection) {
                $collection['tags'] = getCollectionTags($user_id, $collection['title']);
                $book_ids = getCollectionBooks($user_id, $collection['title']);
                $collection['books'] = [];
                foreach ($book_ids as $bookid) {
                    $collection['books'][] = getBookById2($bookid['book_id']); 
                }

                $result[] = $collection;
            }
        }

        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } 
}



?>
