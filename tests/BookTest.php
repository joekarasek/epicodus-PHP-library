<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once __DIR__ . '/../src/Book.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
        }

        function testInfo()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);

            //Act
            $result1 = $test_book->getTitle();
            $result2 = $test_book->getId();

            //Assert
            $this->assertEquals($title, $result1);
            $this->assertEquals($id, $result2);
        }

        function testSave()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);

            //Act
            $test_book->save();

            //Assert
            $result = Book::getAll();
            $this->assertEquals($test_book, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Maggie Pie";
            $test_book2 = new Book($title2, $id);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }
        //
        function testDeleteAll()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();


            $title2 = "Maggie Pie";
            $test_book2 = new Book($title2, $id);
            $test_book2->save();

            //Act
            Book::deleteAll();

            //Assert
            $result = Book::getAll();
            $this->assertEquals([], $result);
        }

        function testFindById()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();


            $title2 = "Maggie Pie";
            $test_book2 = new Book($title2, $id);
            $test_book2->save();

            //Act
            $result = Book::findById($test_book->getId());

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testFindByTitle()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();


            $title2 = "Maggie Pie";
            $test_book2 = new Book($title2, $id);
            $test_book2->save();

            //Act
            $result = Book::findByTitle($test_book->getTitle());

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function testDeleteBook()
        {
            //Arrange
            $title = "Harry Houdinis Magic";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Harry and Maggies Adventures";
            $test_book2 = new Book($title2, $id);
            $test_book2->save();

            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book2->addAuthor($test_author);
            $test_book->delete();

            //Assert
            $this->assertEquals(Book::getAll(), [$test_book2]);
            $this->assertEquals($test_author->getBooks(), [$test_book2]);
        }


        function testAddAuthor()
        {
            //Also tests method getAuthors, test was written but content was redundant

            //Arrange
            $title = "Harry Houdinis Magic";
            $id = null;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Harry and Maggies Adventures";
            $test_book2 = new Book($title2, $id);
            $test_book2->save();

            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();

            $name2 = "Maggie Pie";
            $test_author2 = new Author($name2, $id);
            $test_author2->save();

            //Act
            $test_book->addAuthor($test_author);
            $test_book2->addAuthor($test_author);
            $test_book2->addAuthor($test_author2);

            //Assert
            $this->assertEquals($test_book->getAuthors(), [$test_author]);
            $this->assertEquals($test_book2->getAuthors(), [$test_author, $test_author2]);
        }
    }
 ?>
