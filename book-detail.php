<?php
require("connect-db.php");
require("user-db.php");
require("book-db.php");
require("review-db.php");
require("collection-db.php");
session_start();

if (!isset($_GET['book_id'])) {
    header('Location: book-list.php');
    exit;
}

$bookId = $_GET['book_id'];
$book = getBookById($bookId); 

if (!$book) {
    echo "Book not found";
    exit;
}

$reviews = getReviewsByBookId($bookId);
$authors = getAuthorsByBookId($bookId); 
$genres = getGenresByBookId($bookId);
$collections = getUserCollections($_SESSION['user_id']);
$existingReview = getReviewByUserIdAndBookId($_SESSION['user_id'], $bookId);

require("header.php");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2><?= htmlspecialchars($book['title']) ?></h2>
    <p><strong>Series:</strong> <?= htmlspecialchars($book['series']) ?></p>
    <p><strong>Release Number:</strong> <?= htmlspecialchars($book['release_number']) ?></p>
    <p><strong>Description:</strong> <?= htmlspecialchars($book['description']) ?></p>
    <p><strong>Number of Pages:</strong> <?= htmlspecialchars($book['num_pages']) ?></p>
    <p><strong>Format:</strong> <?= htmlspecialchars($book['format']) ?></p>
    <p><strong>Publication Date:</strong> <?= htmlspecialchars($book['publication_date']) ?></p>
    <p><strong>Rating:</strong> <?= htmlspecialchars($book['rating']) ?> (<?= htmlspecialchars($book['num_voters']) ?> votes)</p>
    <p><strong>Authors:</strong> 
        <?php foreach ($authors as $author): ?>
            <span class="badge bg-primary"><?= $author['author'] ?></span>
        <?php endforeach; ?>
    </p>
    <p><strong>Genres:</strong> 
        <?php foreach ($genres as $genre): ?>
            <span class="badge bg-secondary"><?= $genre['genre'] ?></span>
        <?php endforeach; ?>
    </p>


    

    <form method="post" action="add-book-to-collection.php">
        <div class="mb-3">
            <label for="collection" class="form-label">Choose a Collection:</label>
            <select name="title" class="form-select" id="title" required>
                <option value="">Select Collection</option>
                <?php foreach ($collections as $collection): ?>
                    <option value="<?= htmlspecialchars($collection['title']) ?>"><?= htmlspecialchars($collection['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    
        <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">
        <button type="submit" class="btn btn-primary">Add to Collection</button>
    </form>

    <?php if ($existingReview): ?>
        <form method="post" action="update-review.php">
            <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="1" <?= ($existingReview['rating'] == 1) ? 'selected' : '' ?>>1</option>
                    <option value="2" <?= ($existingReview['rating'] == 2) ? 'selected' : '' ?>>2</option>
                    <option value="3" <?= ($existingReview['rating'] == 3) ? 'selected' : '' ?>>3</option>
                    <option value="4" <?= ($existingReview['rating'] == 4) ? 'selected' : '' ?>>4</option>
                    <option value="5" <?= ($existingReview['rating'] == 5) ? 'selected' : '' ?>>5</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="reviewText" class="form-label">Review</label>
                <textarea name="reviewText" class="form-control" rows="3"><?= htmlspecialchars($existingReview['text']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Review</button>
        </form>
    <?php else: ?>
        <form method="post" action="write-review.php">
            <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="reviewText" class="form-label">Review</label>
                <textarea name="reviewText" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    <?php endif; ?>
    </hr>
    <h3>Reviews</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?= htmlspecialchars($review['user_id']) ?></td>
                    <td><?= htmlspecialchars($review['rating']) ?></td>
                    <td><?= htmlspecialchars($review['text']) ?></td>
                    <td>
                        <?php if ($review['user_id'] == $_SESSION['user_id']): ?>
                            <form method="post" action="delete-review.php">
                                <input type="hidden" name="book_id" value="<?= htmlspecialchars($book['book_id']) ?>">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>
</body>
</html>
