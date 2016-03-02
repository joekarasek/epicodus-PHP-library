<?php
    class Book
    {
        private $title;
        private $id;

        function __construct($title, $id = null)
        {
            $this->title = $title;
            $this->id = $id;
        }

        function getTitle()
        {
            return $this->title;
        }

        function setTitle($new_title)
        {
            $this->title = $new_title;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addAuthor($new_author)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$this->getId()}, {$new_author->getId()});");
        }

        function getAuthors()
        {
            $query = $GLOBALS['DB']->query("SELECT authors.* FROM books
                JOIN books_authors ON (books.id = books_authors.book_id)
                JOIN authors ON (books_authors.author_id = authors.id)
                WHERE books.id = {$this->getId()};");
            $author_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $authors = array();
            foreach($author_ids as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getId()};");
            // $GLOBALS['DB']->exec("DELETE FROM books_courses WHERE book_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function findById($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                  $found_book = $book;
                }
            }
            return $found_book;
        }

        static function findByTitle($search_title)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_title = $book->getTitle();
                if ($book_title == $search_title) {
                  $found_book = $book;
                }
            }
            return $found_book;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec('DELETE FROM books;');
        }

    }
?>
