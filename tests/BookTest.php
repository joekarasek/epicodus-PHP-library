<!-- testing with php unit, use this template for guidance -->
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
        }

        function testInfo()
        {
            //Arrange
            $title = "Harry Houdini";
            $id = null;
            $test_book = new Book($title, $id);

            //Act
            $result1 = $test_book->getName();
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
        //
        // function testFind()
        // {
        //     //Arrange
        //     $title = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_book = new Book($title, $enrollment_date, $id);
        //     $test_book->save();
        //
        //
        //     $title2 = "Maggie Pie";
        //     $enrollment_date2 = '2016-03-05';
        //     $id2 = 2;
        //     $test_book2 = new Book($title2, $enrollment_date2, $id2);
        //     $test_book2->save();
        //
        //     //Act
        //     $result = Book::find($test_book->getId());
        //
        //     //Assert
        //     $this->assertEquals($test_book, $result);
        // }
        //
        //
        // function testDeleteBook()
        // {
        //     //Arrange
        //     $title = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_book = new Book($title, $enrollment_date, $id);
        //     $test_book->save();
        //
        //
        //     $title2 = "Maggie Pie";
        //     $enrollment_date2 = '2016-03-05';
        //     $id2 = 2;
        //     $test_book2 = new Book($title2, $enrollment_date2, $id2);
        //     $test_book2->save();
        //
        //
        //     //Act
        //     $test_book->delete();
        //
        //     //Assert
        //     $this->assertEquals(Book::getAll(), [$test_book2]);
        // }
        //
        //
        // function testAddCourse()
        // {
        //     //Arrange
        //     $subject = "Chemistry 101";
        //     $course_number = 'CHEM101';
        //     $id = 1;
        //     $test_course = new Course($subject, $course_number, $id);
        //     $test_course->save();
        //
        //     $title = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_book = new Book($title, $enrollment_date, $id);
        //     $test_book->save();
        //
        //     //Act
        //     $test_book->addCourse($test_course);
        //
        //     //Assert
        //     $this->assertEquals($test_book->getCourses(), [$test_course]);
        // }
        //
        // function testGetCourses()
        // {
        //     //Arrange
        //     $title = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_book = new Book($title, $enrollment_date, $id);
        //     $test_book->save();
        //
        //
        //     $subject2 = "Chemistry 101";
        //     $course_number = 'CHEM101';
        //     $id2 = 1;
        //     $test_course = new Course($subject2, $course_number, $id2);
        //     $test_course->save();
        //
        //     $subject3 = "Chemistry 103";
        //     $course_number2 = 'CHEM103';
        //     $id3 = 2;
        //     $test_course2 = new Course($subject3, $course_number, $id3);
        //     $test_course2->save();
        //
        //     //Act
        //     $test_book->addCourse($test_course);
        //     $test_book->addCourse($test_course2);
        //
        //     //Assert
        //     $this->assertEquals($test_book->getCourses(), [$test_course, $test_course2]);
        // }
        //
        // function testDelete()
        // {
        //     //Arrange
        //     $subject = "Math";
        //     $course_number = "MTH101";
        //     $id = 1;
        //     $test_course = new Course($subject, $course_number, $id);
        //     $test_course->save();
        //
        //     $title = "Maggie Pie";
        //     $enrollment_date = '2016-03-05';
        //     $id2 = 2;
        //     $test_book = new Book($title, $enrollment_date, $id2);
        //     $test_book->save();
        //
        //     //Act
        //     $test_book->addCourse($test_course);
        //     $test_book->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_course->getBooks());
        // }

    }
 ?>
