<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Patron.php";
    require_once __DIR__."/../src/Book.php";

    $app = new Silex\Application();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    // setup server for database
    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    // allow patch and delete request to be handled by browser
    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array(
            'navbar' => true
        ));
    });

    $app->get("/librarian", function() use ($app) {
        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true
        ));
    });

    $app->get("/librarian/books", function() use ($app) {
        $books = Book::getAll();

        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true,
            'books' => $books,
            'bookform' => true
        ));
    });

    $app->post("/librarian/addBook", function() use ($app) {
        if (!Book::findByTitle($_POST['book-title']))
        {
            $new_book = new Book($_POST['book-title']);
            $new_book->save();

            if (Author::findByName($_POST['book-author']))
            {
                $new_book->addAuthor(Author::findByName($_POST['book-author']));
            } else {
                $new_author = new Author($_POST['book-author']);
                $new_author->save();
                $new_book->addAuthor($new_author);
            }

        } else {
            $message = array(
                'type' => 'danger',
                'text' => 'That title already exists. Book not added to catalog.'
            );
        }

        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true,
            'books' => Book::getAll(),
            'bookform' => true,
            'message' => $message
        ));
    });

    $app->get("/librarian/book/{book_id}", function($book_id) use ($app) {
        $book = Book::findById($book_id);
        $copies = $book->getCopies();

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true,
            'copies' => $copies
        ));
    });

    $app->patch("/librarian/book/{book_id}/updateTitle", function($book_id) use ($app) {
        $book = Book::findById($book_id);
        $book->updateTitle($_POST['new-book-title']);

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true,
            'message' => array(
                'type' => 'info',
                'text' => 'Book title updated!'
            )
        ));
    });

    $app->post("/librarian/book/{book_id}/addCopy", function($book_id) use ($app) {
        $book = Book::findById($book_id);
        $book->addCopy();
        $copies = $book->getCopies();

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true,
            'message' => array(
                'type' => 'warning',
                'text' => 'Number of copies in catalog updated'
            ),
            'copies' => $copies
        ));
    });

    $app->delete("/librarian/book/{book_id}/deleteCopy/{copy_id}", function($book_id, $copy_id) use ($app) {
        $book = Book::findById($book_id);
        $book->deleteCopy($copy_id);
        $copies = $book->getCopies();

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true,
            'message' => array(
                'type' => 'danger',
                'text' => 'You removed a copy from the catalog'
            ),
            'copies' => $copies
        ));
    });

    $app->get("/librarian/patrons", function() use ($app) {
        $patrons = Patron::getAll();

        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true,
            'patrons' => $patrons,
            'patronform' => true
        ));
    });

    $app->post("/librarian/addPatron", function() use ($app) {
        if (!Patron::findByName($_POST['patron-name']))
        {
            $new_patron = new Patron($_POST['patron-name'], $_POST['patron-email']);
            $new_patron->save();
            $message = array(
                'type' => 'info',
                'text' => $_POST['patron-name'] . ' was added to the database.'
            );
        } else {
            $message = array(
                'type' => 'danger',
                'text' => 'That patron already exists. Patron not added to database.'
            );
        }

        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true,
            'patrons' => Patron::getAll(),
            'patronform' => true,
            'message' => $message
        ));
    });

    $app->get("/librarian/patron/{patron_id}", function($patron_id) use ($app) {
        $patron = Patron::findById($patron_id);

        return $app['twig']->render('librarian-patron.html.twig', array(
            'navbar' => true,
            'patron' => $patron,
            'form' => true
        ));
    });

    $app->patch("/librarian/patron/{patron_id}/update", function($patron_id) use ($app) {
        $patron = Patron::findById($patron_id);
        $patron->update($_POST['new-patron-name'], $_POST['new-patron-email']);

        return $app['twig']->render('librarian-patron.html.twig', array(
            'navbar' => true,
            'patron' => $patron,
            'form' => true,
            'message' => array(
                'type' => 'info',
                'text' => 'Patron information updated!'
            )
        ));
    });

    $app->delete("/librarian/patron/{patron_id}/deletePatron", function($patron_id) use ($app) {
        $patron = Patron::findById($patron_id);
        $patron->deletePatron();

        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true,
            'patrons' => Patron::getAll(),
            'form' => true,
            'message' => array(
                'type' => 'danger',
                'text' => 'You removed a patron'
            )
        ));
    });

    $app->get("/patron", function() use ($app) {
        $patrons = Patron::getAll();
        return $app['twig']->render('patron.html.twig', array(
            'patrons' => $patrons,
            'message' => array(
                'type' => 'info',
                'text' => 'Click on your own name!'
            )
        ));
    });

    $app->get("/patron/{patron_id}/books", function($patron_id) use ($app) {
        $patron = Patron::findbyId($patron_id);
        $books = Book::getAll();

        return $app['twig']->render('patron.html.twig', array(
            'patron' => $patron,
            'navbar' => true,
            'books' => $books
        ));
    });

    $app->get("/patron/{patron_id}/book/{book_id}", function($patron_id, $book_id) use ($app) {
        $patron = Patron::findbyId($patron_id);
        $book = Book::findById($book_id);

        return $app['twig']->render('patron-book.html.twig', array(
            'patron' => $patron,
            'navbar' => true,
            'book' => $book
        ));
    });

    $app->post("/patron/{patron_id}/book/{book_id}/checkout", function($patron_id, $book_id) use ($app) {
        $patron = Patron::findbyId($patron_id);
        $book = Book::findById($book_id);
        $book->checkout($patron_id);

        return $app['twig']->render('patron-book.html.twig', array(
            'patron' => $patron,
            'navbar' => true,
            'book' => $book,
            'message' => array(
                'type' => 'info',
                'text' => 'You checked out a copy of this book'
            )
        ));
    });

    $app->get("/patron/{patron_id}/checkouts", function($patron_id) use ($app) {
        $patron = Patron::findbyId($patron_id);
        $checkouts = $patron->getCheckouts();

        return $app['twig']->render('patron-checkouts.html.twig', array(
            'patron' => $patron,
            'navbar' => true,
            'checkouts' => $checkouts
        ));
    });

    $app->patch("/patron/{patron_id}/checkouts/{book_id}/return", function($patron_id, $book_id) use ($app) {
        $patron = Patron::findbyId($patron_id);
        $book = Book::findById($book_id);
        $book->returnCopy($_POST['copy_id']);
        $checkouts = $patron->getCheckouts();

        return $app['twig']->render('patron-checkouts.html.twig', array(
            'patron' => $patron,
            'navbar' => true,
            'checkouts' => $checkouts,
            'message' => array(
                'type' => 'info',
                'text' => 'You returned a copy of ' . $book->getTitle()
            )
        ));
    });










    return $app;
?>
