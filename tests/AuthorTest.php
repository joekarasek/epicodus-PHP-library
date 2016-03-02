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


        // function testDeleteAuthor()
        // {
        //     //Arrange
        //     $name = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_author = new Author($name, $enrollment_date, $id);
        //     $test_author->save();
        //
        //
        //     $name2 = "Maggie Pie";
        //     $enrollment_date2 = '2016-03-05';
        //     $id2 = 2;
        //     $test_author2 = new Author($name2, $enrollment_date2, $id2);
        //     $test_author2->save();
        //
        //
        //     //Act
        //     $test_author->delete();
        //
        //     //Assert
        //     $this->assertEquals(Author::getAll(), [$test_author2]);
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
        //     $name = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_author = new Author($name, $enrollment_date, $id);
        //     $test_author->save();
        //
        //     //Act
        //     $test_author->addCourse($test_course);
        //
        //     //Assert
        //     $this->assertEquals($test_author->getCourses(), [$test_course]);
        // }
        //
        // function testGetCourses()
        // {
        //     //Arrange
        //     $name = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_author = new Author($name, $enrollment_date, $id);
        //     $test_author->save();
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
        //     $test_author->addCourse($test_course);
        //     $test_author->addCourse($test_course2);
        //
        //     //Assert
        //     $this->assertEquals($test_author->getCourses(), [$test_course, $test_course2]);
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
        //     $name = "Maggie Pie";
        //     $enrollment_date = '2016-03-05';
        //     $id2 = 2;
        //     $test_author = new Author($name, $enrollment_date, $id2);
        //     $test_author->save();
        //
        //     //Act
        //     $test_author->addCourse($test_course);
        //     $test_author->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_course->getAuthors());
        // }

    }
 ?>
