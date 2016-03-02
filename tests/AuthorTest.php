<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once __DIR__ . '/../src/Author.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }

        function testInfo()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);

            //Act
            $result1 = $test_author->getName();
            $result2 = $test_author->getId();

            //Assert
            $this->assertEquals($name, $result1);
            $this->assertEquals($id, $result2);
        }

        function testSave()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);

            //Act
            $test_author->save();

            //Assert
            $result = Author::getAll();
            $this->assertEquals($test_author, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();

            $name2 = "Maggie Pie";
            $test_author2 = new Author($name2, $id);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }
        //
        function testDeleteAll()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();


            $name2 = "Maggie Pie";
            $test_author2 = new Author($name2, $id);
            $test_author2->save();

            //Act
            Author::deleteAll();

            //Assert
            $result = Author::getAll();
            $this->assertEquals([], $result);
        }

        function testFindById()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();


            $name2 = "Maggie Pie";
            $test_author2 = new Author($name2, $id);
            $test_author2->save();

            //Act
            $result = Author::findById($test_author->getId());

            //Assert
            $this->assertEquals($test_author, $result);
        }

        function testFindByName()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();


            $name2 = "Maggie Pie";
            $test_author2 = new Author($name2, $id);
            $test_author2->save();

            //Act
            $result = Author::findByName($test_author->getName());

            //Assert
            $this->assertEquals($test_author, $result);
        }

        function testAddBook()
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
            $test_author->addBook($test_book);
            $test_author->addBook($test_book2);
            $test_author2->addBook($test_book2);

            //Assert
            $this->assertEquals($test_author->getBooks(), [$test_book, $test_book2]);
            $this->assertEquals($test_author2->getBooks(), [$test_book2]);
        }

        function testDeleteAuthor()
        {
            //Arrange
            $name = "Harry Houdini";
            $id = null;
            $test_author = new Author($name, $id);
            $test_author->save();

            $name2 = "Maggie Pie";
            $test_author2 = new Author($name2, $id);
            $test_author2->save();

            //Act
            $test_author->deleteAuthor();

            //Assert
            $this->assertEquals(Author::getAll(), [$test_author2]);
        }

    }
 ?>
