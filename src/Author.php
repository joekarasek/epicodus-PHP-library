<?php
    class Author
    {
        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function getName()
        {
            return $this->name;
        }

        function setName($new_name)
        {
            $this->name = $new_name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO authors (name) VALUES ('{$this->getName()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function addBook($new_book)
        {
            $GLOBALS['DB']->exec("INSERT INTO books_authors (book_id, author_id) VALUES ({$new_book->getId()}, {$this->getId()});");
        }

        function getBooks()
        {
            $query = $GLOBALS['DB']->query("SELECT books.* FROM authors
                JOIN books_authors ON (authors.id = books_authors.author_id)
                JOIN books ON (books_authors.book_id = books.id)
                WHERE authors.id = {$this->getId()};");
            $book_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $books = array();
            foreach($book_ids as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        function deleteAuthor()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE author_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function findById($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        static function findByName($search_name)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_name = $author->getName();
                if ($author_name == $search_name) {
                  $found_author = $author;
                }
            }
            return $found_author;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec('DELETE FROM authors;');
        }

    }
?>
