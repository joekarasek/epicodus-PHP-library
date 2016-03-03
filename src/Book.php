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

        function updateTitle($new_title)
        {
              $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
              $this->setTitle($new_title);
        }

        function addCopy()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id, checked_out) VALUES ({$this->getId()}, 0);");
        }

        function deleteCopy()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE book_id = {$this->getId()} LIMIT 1;");
        }

        function checkout()
        {
            if($this->countCopiesAvailable()>0) {
                $GLOBALS['DB']->exec("UPDATE copies SET checked_out = 1 WHERE book_id = {$this->getId()} AND checked_out = 0 LIMIT 1;");
                // $GLOBALS['DB']->exec("INSERT INTO checkouts ")
            }
        }

        function countCopies()
        {
            $query = $GLOBALS['DB']->query("SELECT copies.* FROM
                books JOIN copies ON (books.id = copies.book_id)
                WHERE books.id = {$this->getId()}");
            $copies = $query->fetchAll(PDO::FETCH_ASSOC);
            $copies_count = 0;
            foreach ($copies as $copy) {
                $copies_count++;
            }
            return $copies_count;
        }

        function countCopiesAvailable()
        {
            $query = $GLOBALS['DB']->query("SELECT copies.* FROM
                books JOIN copies ON (books.id = copies.book_id)
                WHERE books.id = {$this->getId()}");
            $copies = $query->fetchAll(PDO::FETCH_ASSOC);
            $copies_count = 0;
            foreach ($copies as $copy) {
                if($copy['checked_out'] == 0) {
                    $copies_count++;
                }
            }
            return $copies_count;
        }

        //
        // function getCopies()
        // {
        //     $query = $GLOBALS['DB']->query("SELECT copies.* FROM
        //         books JOIN copies ON (books.id = copies.book_id)
        //         WHERE books.id = {$this->getId()}");
        //     $copies = $query->fetchAll(PDO::FETCH_ASSOC);
        //     return $copies;
        // }

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
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE book_id = {$this->getId()};");
            //ASK JOHN ABOUT HOW TO TEST THIS LINE
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
