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
            // twig input associative array
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

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true
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

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true,
            'message' => array(
                'type' => 'warning',
                'text' => 'Number of copies in catalog updated'
            )
        ));
    });

    $app->post("/librarian/book/{book_id}/deleteCopy", function($book_id) use ($app) {
        $book = Book::findById($book_id);
        $book->deleteCopy();

        return $app['twig']->render('librarian-book.html.twig', array(
            'navbar' => true,
            'book' => $book,
            'form' => true,
            'message' => array(
                'type' => 'danger',
                'text' => 'You removed a copy from the catalog'
            )
        ));
    });

    return $app;
?>
