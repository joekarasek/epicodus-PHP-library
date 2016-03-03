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

        function deleteCopy($copy_id)
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$copy_id};");
            $GLOBALS['DB']->exec("UPDATE checkouts SET returned = 1 WHERE copy_id = {$copy_id};");
        }

        function checkout($patron_id)
        {
            if($this->countCopiesAvailable()>0) {
                $query = $GLOBALS['DB']->query("SELECT id FROM copies WHERE book_id = {$this->getId()} AND checked_out = 0 LIMIT 1;");
                $copy_mid_step = $query->fetchAll(PDO::FETCH_ASSOC);
                $copy_id = $copy_mid_step[0]['id'];
                $GLOBALS['DB']->exec("UPDATE copies SET checked_out = 1 WHERE book_id = {$this->getId()} AND checked_out = 0 LIMIT 1;");
                $due_date = date('Y-m-d', strtotime('+1 week'));
                $GLOBALS['DB']->exec("INSERT INTO checkouts (book_id, patron_id, copy_id, due_date, returned) VALUES ({$this->getId()}, {$patron_id}, {$copy_id}, '{$due_date}', 0);");
            }
        }

        function returnCopy($copy_id)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET checked_out = 0 WHERE book_id = {$this->getId()} AND checked_out = 1 LIMIT 1;");
            $GLOBALS['DB']->exec("UPDATE checkouts SET returned = 1 WHERE copy_id = {$copy_id} AND returned = 0 LIMIT 1;");
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

        function getCopies()
        {
            $query = $GLOBALS['DB']->query("SELECT copies.* FROM
                books JOIN copies ON (books.id = copies.book_id)
                WHERE books.id = {$this->getId()}");
            $copies = $query->fetchAll(PDO::FETCH_ASSOC);
            $copies_array = array();
            foreach($copies as $copy) {
                $id = $copy['id'];
                $checked_out = $copy['checked_out'];
                $query = $GLOBALS['DB']->query("SELECT * FROM checkouts WHERE copy_id = {$id} AND returned = 0;");
                $patron_array = $query->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($patron_array)) {
                    if ($checked_out) {
                        $patron_id = $patron_array[0]['patron_id'];
                        $patron = Patron::findbyId($patron_id);
                        $patron_name = $patron->getName();
                        $due_date = $patron_array[0]['due_date'];
                    } else {
                        $patron_name = "The Library";
                    }
                } else {
                    $patron_name = "The Library";
                    $due_date = "2050-01-01";
                }

                $book_id = $this->getId();
                $copy_entry = array(
                    'copy_id' => $id,
                    'checked_out' => $checked_out,
                    'owner_name' => $patron_name,
                    'due_date' => $due_date,
                    'over_due' => $this->overdue($id)
                    );
                array_push($copies_array, $copy_entry);
            }

            return $copies_array;
        }

        function overdue($copy_id)
        {
            $today = date('Y-m-d', strtotime('now'));
            $query = $GLOBALS['DB']->query("SELECT due_date FROM checkouts WHERE copy_id = {$copy_id} AND returned = 0;");
            // var_dump($due_date_mid_step['due_date']);
            $due_date_mid_step = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($due_date_mid_step)) {
                $due_date = $due_date_mid_step[0]['due_date'];
            } else {
                $due_date = '2050-01-01';
            }

            if($today > $due_date) {
                return true;
            } else {
                return false;
            }
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
            $GLOBALS['DB']->exec("DELETE FROM books_authors WHERE book_id = {$this->getId()};");
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
