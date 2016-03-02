<?php
    class Patron
    {
        private $name;
        private $email;
        private $id;

        function __construct($name, $email, $id = null)
        {
            $this->name = $name;
            $this->email = $email;
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

        function getEmail()
        {
            return $this->email;
        }

        function setEmail($new_email)
        {
            $this->email = $new_email;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
              $GLOBALS['DB']->exec("INSERT INTO patrons (name, email) VALUES ('{$this->getName()}', '{$this->getEmail()}');");
              $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name, $new_email)
        {
            $GLOBALS['DB']->exec("UPDATE patrons SET name = '{$new_name}', email = '{$new_email}' WHERE id = {$this->getId()};");
            $this->setName($new_name);
            $this->setEmail($new_email);
        }

        function deletePatron()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons WHERE id = {$this->getId()};");
            // $GLOBALS['DB']->exec("DELETE FROM students_courses WHERE student_id = {$this->getId()};");
        }

        static function getAll()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT * FROM patrons;");
            $patrons = array();
            foreach($returned_patrons as $patron) {
                $name = $patron['name'];
                $email = $patron['email'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $email, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        static function findById($search_id)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_id = $patron->getId();
                if ($patron_id == $search_id) {
                  $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        static function findByName($search_name)
        {
            $found_patron = null;
            $patrons = Patron::getAll();
            foreach($patrons as $patron) {
                $patron_name = $patron->getName();
                if ($patron_name == $search_name) {
                  $found_patron = $patron;
                }
            }
            return $found_patron;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec('DELETE FROM patrons;');
        }

    }
?>
