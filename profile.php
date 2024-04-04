<?php
require("connect-db.php");
require("user-db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$favorites = getUserFavoriteBooks($user_id);
$collections = getUserCollections($user_id); 
$genres = getUserFavoriteGenres($user_id);

require("header.php");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>User Profile</h2>
    <p><strong>User ID:</strong> <?= htmlspecialchars($user_id) ?></p>
    <!-- Display more user info if needed -->

    <h3>Add Favorite Book</h3>
    <form method="post" action="add-favorite-book.php">
        <input type="text" name="book_name" placeholder="Book Name" required>
        <button type="submit" class="btn btn-primary">Add Favorite</button>
    </form>

    <h3>Add Favorite Genre</h3>
    <form method="post" action="add-favorite-genre.php">
        <input type="text" name="genre" placeholder="Genre" required>
        <button type="submit" class="btn btn-primary">Add Favorite Genre</button>
    </form>


    <h3>Create Collection</h3>
    <form method="post" action="create-collection.php">
        <input type="text" name="title" placeholder="Collection Title" required>
        <button type="submit" class="btn btn-primary">Create Collection</button>
    </form>

    <h3>My Favorite Books</h3>
    <ul>
        <?php foreach ($favorites as $favorite): ?>
            <li class="d-flex justify-content-between">
                <span><?= htmlspecialchars($favorite['book_name']) ?></span>
                <form method="post" action="remove-favorite-book.php">
                    <input type="hidden" name="book_name" value="<?= htmlspecialchars($favorite['book_name']) ?>">
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>My Favorite Genres</h3>
    <ul>
        <?php foreach ($genres as $genre): ?>
            <li class="d-flex justify-content-between">
                <span><?= htmlspecialchars($genre['genre']) ?></span>
                <form method="post" action="remove-favorite-genre.php">
                    <input type="hidden" name="genre" value="<?= htmlspecialchars($genre['genre']) ?>">
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

        
    <h3>My Collections</h3>
    <ul>
        <?php foreach ($collections as $collection): ?>
            <li><?= htmlspecialchars($collection['title']) ?></li>
            <?php foreach ($collection['tags'] as $tag): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
            <?php if (!empty($collection['books'])): ?>
                <div class="mb-3">Books in this collection:</div>
                <ul>
                    <?php foreach ($collection['books'] as $book): ?>
                        <li><a href="book-detail.php?book_id=<?= htmlspecialchars($book['book_id']) ?>"><?= htmlspecialchars($book['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <form method="post" action="delete-collection.php">
                <input type="hidden" name="title" value="<?= htmlspecialchars($collection['title']) ?>">
                <button type="submit" class="btn btn-danger">Delete Collection</button>
            </form>
            <form method="post" action="add-collection-tag.php">
                <input type="hidden" name="title" value="<?= htmlspecialchars($collection['title']) ?>">
                <input type="text" name="tag" placeholder="Tag" required>
                <button type="submit" class="btn btn-primary">Add Tag</button>
            </form>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
