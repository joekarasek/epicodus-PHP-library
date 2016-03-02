<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once __DIR__ . '/../src/Patron.php';

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Patron::deleteAll();
        }

        function testInfo()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);

            //Act
            $result1 = $test_patron->getName();
            $result2 = $test_patron->getId();
            $result3 = $test_patron->getEmail();

            //Assert
            $this->assertEquals($name, $result1);
            $this->assertEquals($id, $result2);
            $this->assertEquals($email, $result3);
        }

        function testSave()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);

            //Act
            $test_patron->save();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals($test_patron, $result[0]);
        }

        function testGetAll()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);
            $test_patron->save();

            $name2 = "Maggie Pie";
            $email2 = "maggie@pie.com";
            $test_patron2 = new Patron($name2, $email2, $id);
            $test_patron2->save();

            //Act
            $result = Patron::getAll();

            //Assert
            $this->assertEquals([$test_patron, $test_patron2], $result);
        }
        //
        function testDeleteAll()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);
            $test_patron->save();

            $name2 = "Maggie Pie";
            $email2 = "maggie@pie.com";
            $test_patron2 = new Patron($name2, $email2, $id);
            $test_patron2->save();

            //Act
            Patron::deleteAll();

            //Assert
            $result = Patron::getAll();
            $this->assertEquals([], $result);
        }

        function testFindById()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);
            $test_patron->save();

            $name2 = "Maggie Pie";
            $email2 = "maggie@pie.com";
            $test_patron2 = new Patron($name2, $email2, $id);
            $test_patron2->save();

            //Act
            $result = Patron::findById($test_patron->getId());

            //Assert
            $this->assertEquals($test_patron, $result);
        }

        function testFindByName()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);
            $test_patron->save();

            $name2 = "Maggie Pie";
            $email2 = "maggie@pie.com";
            $test_patron2 = new Patron($name2, $email2, $id);
            $test_patron2->save();

            //Act
            $result = Patron::findByName($test_patron->getName());

            //Assert
            $this->assertEquals($test_patron, $result);
        }

        function testDeletePatron()
        {
            //Arrange
            $name = "Harry Houdini";
            $email = "harry@houdini.com";
            $id = null;
            $test_patron = new Patron($name, $email, $id);
            $test_patron->save();

            $name2 = "Maggie Pie";
            $email2 = "maggie@pie.com";
            $test_patron2 = new Patron($name2, $email2, $id);
            $test_patron2->save();
        
            //Act
            $test_patron->deletePatron();

            //Assert
            $this->assertEquals(Patron::getAll(), [$test_patron2]);
        }


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
        //     $test_patron = new Patron($name, $enrollment_date, $id);
        //     $test_patron->save();
        //
        //     //Act
        //     $test_patron->addCourse($test_course);
        //
        //     //Assert
        //     $this->assertEquals($test_patron->getCourses(), [$test_course]);
        // }
        //
        // function testGetCourses()
        // {
        //     //Arrange
        //     $name = "Harry Houdini";
        //
        //     $id = 1;
        //     $test_patron = new Patron($name, $enrollment_date, $id);
        //     $test_patron->save();
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
        //     $test_patron->addCourse($test_course);
        //     $test_patron->addCourse($test_course2);
        //
        //     //Assert
        //     $this->assertEquals($test_patron->getCourses(), [$test_course, $test_course2]);
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
        //     $test_patron = new Patron($name, $enrollment_date, $id2);
        //     $test_patron->save();
        //
        //     //Act
        //     $test_patron->addCourse($test_course);
        //     $test_patron->delete();
        //
        //     //Assert
        //     $this->assertEquals([], $test_course->getPatrons());
        // }

    }
 ?>
