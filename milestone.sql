-- create db
CREATE DATABASE project_db;
USE project_db;


-- Create tables 

CREATE TABLE IF NOT EXISTS user (
    user_id VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id)
);


CREATE TABLE IF NOT EXISTS user_favorite_book (
    user_id VARCHAR(255) NOT NULL,
    book_name VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, book_name)
);


CREATE TABLE IF NOT EXISTS user_favorite_genre (
    user_id VARCHAR(255) NOT NULL,
    genre VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, genre)
);

CREATE TABLE IF NOT EXISTS book (
    book_id VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    series VARCHAR(255),
    release_number INT DEFAULT 1,
    description TEXT,
    num_pages INT,
    format VARCHAR(255),
    publication_date DATE,
    rating DECIMAL(3, 2) DEFAULT 0.0,
    num_voters INT DEFAULT 0,
    PRIMARY KEY (book_id)
);


CREATE TABLE IF NOT EXISTS review (
    user_id VARCHAR(255) NOT NULL,
    book_id VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    text TEXT NOT NULL,
    rating INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (book_id) REFERENCES book(book_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, book_id)
);



CREATE TABLE IF NOT EXISTS collection (
    user_id VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, title)
);


CREATE TABLE IF NOT EXISTS collection_tags (
    user_id VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    tag VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id, title) REFERENCES collection(user_id, title) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (user_id, title, tag)
);


CREATE TABLE IF NOT EXISTS book_author (
    book_id VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    FOREIGN KEY (book_id) REFERENCES book(book_id),
    PRIMARY KEY (book_id, author)
);

CREATE TABLE IF NOT EXISTS book_genre (
    book_id VARCHAR(255) NOT NULL,
    genre VARCHAR(255) NOT NULL,
    FOREIGN KEY (book_id) REFERENCES book(book_id),
    PRIMARY KEY (book_id, genre)
);


CREATE TABLE IF NOT EXISTS has (
    user_id VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    book_id VARCHAR(255) NOT NULL,
    added_timestamp TIMESTAMP NOT NULL,
    PRIMARY KEY (user_id, title, book_id),
    FOREIGN KEY (user_id, title) REFERENCES collection(user_id, title) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (book_id) REFERENCES book(book_id)
);




-- load book data from book.csv
LOAD DATA LOCAL INFILE 'book.csv' INTO TABLE book
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(book_id, title, series, release_number, description, num_pages, format, publication_date, rating, num_voters);


-- load book_author data from book_author.csv
LOAD DATA LOCAL INFILE 'book_author.csv' INTO TABLE book_author
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(book_id, author);

-- load book_genre data from book_genre_clean.csv
LOAD DATA LOCAL INFILE 'book_genre_clean.csv' INTO TABLE book_genre
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(book_id, genre);

-- Example Commands that will be used in our app:

-- Data Retrieval:

-- View Detailed Information About Each Book (book 1 for example)
SELECT * FROM book WHERE book_id = '1';

-- List Reviews for a Book
SELECT * FROM review WHERE book_id = '1';


-- List Books in a User's Collection (user 001 for example)
SELECT title, book_id FROM has WHERE user_id = '001';

-- Data Addition

-- Add a Book to a User’s Collection
INSERT INTO has (user_id, title, book_id, added_timestamp) VALUES ('001', 'My Favorites', '2', NOW());

-- Add a Review
INSERT INTO review (user_id, book_id, date, text, rating) VALUES ('001', '2', '2023-03-01', 'Interesting read.', 4);


-- Data Modification

-- Modify a Review
UPDATE review SET text = 'Updated review text.' WHERE user_id = '001' AND book_id = '2';

-- Data Deletion

-- Remove a Book from User’s Collection
DELETE FROM has WHERE user_id = '001' AND book_id = '2';


-- Delete a Review
DELETE FROM review WHERE user_id = '001' AND book_id = '2';

-- Sorting and Filtering


-- Sort Books by Rating
SELECT * FROM book ORDER BY rating DESC;

-- Filter Books by Genre
SELECT DISTINCT B.title 
FROM book B
JOIN book_genre BG ON B.book_id = BG.book_id
WHERE BG.genre = 'Fantasy'; 

-- Search Functionality

-- Search Books by Title, Author, or Genre
-- SELECT * FROM book WHERE title LIKE '%Book%' OR book_id IN (SELECT book_id FROM book_author WHERE author LIKE '%Author%') OR book_id IN (SELECT book_id FROM book_genre WHERE genre LIKE '%Genre%');


-- check constraint

ALTER TABLE review
ADD CONSTRAINT chk_rating 
CHECK (rating >= 0 AND rating <= 5);

-- Trigger update book rating when new review is added

DELIMITER $$ 
CREATE TRIGGER update_book_rating
AFTER INSERT ON review
FOR EACH ROW
BEGIN
    UPDATE book
    SET rating = (SELECT AVG(rating) FROM review WHERE book_id = NEW.book_id), num_voters = (SELECT COUNT(*) FROM review WHERE book_id = NEW.book_id)
    WHERE book_id = NEW.book_id;
END$$
DELIMITER ;


-- show schemas for each table
DESC user;
DESC user_favorite_book;
DESC user_favorite_genre;
DESC book;
DESC review;
DESC collection;
DESC collection_tags;
DESC book_author;
DESC book_genre;
DESC has;

-- show number of rows for each table
SELECT COUNT(*) from user;
SELECT COUNT(*) from user_favorite_book;
SELECT COUNT(*) from user_favorite_genre;
SELECT COUNT(*) from book;
SELECT COUNT(*) from review;
SELECT COUNT(*) from collection;
SELECT COUNT(*) from collection_tags;
SELECT COUNT(*) from book_author;
SELECT COUNT(*) from book_genre;
SELECT COUNT(*) from has;

-- show first 5 rows for each table
SELECT * FROM user LIMIT 5;
SELECT * FROM user_favorite_book LIMIT 5;
SELECT * FROM user_favorite_genre LIMIT 5;
SELECT * FROM book LIMIT 5;
SELECT * FROM review LIMIT 5;
SELECT * FROM collection LIMIT 5;
SELECT * FROM collection_tags LIMIT 5;
SELECT * FROM book_author LIMIT 5;
SELECT * FROM book_genre LIMIT 5;
SELECT * FROM has LIMIT 5;

