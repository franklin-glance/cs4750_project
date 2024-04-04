<?php
require("connect-db.php");
require("user-db.php");
require("book-db.php");
session_start();



$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 15; 

$books = getBooksByTitle($page, $itemsPerPage, $_GET['searchTerm'] ?? '', $_GET['searchColumn'] ?? 'title');

$totalBooks = getTotalBooks();
$numresults = count($books);
 
$totalPages = ceil($totalBooks / $itemsPerPage);

require("header.php");
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Book List</h2>
    <form action="" method="get" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <select name="searchColumn" class="form-select">
                    <option value="title">Title</option>
                    <option value="series">Series</option>
                    <option value="format">Format</option>
                    <!-- Add more options based on your table columns -->
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="searchTerm" class="form-control" placeholder="Search term" value="<?= htmlspecialchars($_GET['searchTerm'] ?? '') ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

<nav aria-label="Page navigation example">
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
        <?php endif; ?>
        <?php if ($page < $totalPages): ?>
            <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
        <?php endif; ?>
    </ul>
</nav>



<p>Showing <?= count($books) ?> of <?= $totalBooks ?> books</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Authors</th>
                    <th>Genres</th>
                    <th>Series</th>
                    <th>Release Number</th>
                    <th>Num Pages</th>
                    <th>Format</th>
                    <th>Publication Date</th>
                    <th>Rating</th>
                    <th>Num Voters</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><a href="book-detail.php?book_id=<?= urlencode($book['book_id']) ?>"><?= htmlspecialchars($book['title']) ?></a></td>
                        <td>
                            <?php foreach (getAuthorsByBookId($book['book_id']) as $author): ?>
                                <span class="badge bg-primary"><?= htmlspecialchars($author['author']) ?></span>
                            <?php endforeach; ?>
                        </td>
                        <td>
                            <?php foreach (getGenresByBookId($book['book_id']) as $genre): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($genre['genre']) ?></span>
                            <?php endforeach; ?>
                        </td>

                        <td><?= htmlspecialchars($book['series']) ?></td>
                        <td><?= htmlspecialchars($book['release_number']) ?></td>
                        <td><?= htmlspecialchars($book['num_pages']) ?></td>
                        <td><?= htmlspecialchars($book['format']) ?></td>
                        <td><?= htmlspecialchars($book['publication_date']) ?></td>
                        <td><?= htmlspecialchars($book['rating']) ?></td>
                        <td><?= htmlspecialchars($book['num_voters']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


<form action="" method="get" class="mb-4">
    <div class="row g-3 align-items-center">
        <label for="itemsPerPage" class="col-auto">Books per page:</label>
        <div class="col-auto">
            <select name="itemsPerPage" class="form-select">
                <option value="5">5</option>
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

    </div>
</body>
</html>
