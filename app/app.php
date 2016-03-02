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

        return $app['twig']->render('librarian.html.twig', array(
            'navbar' => true,
            'books' => Book::getAll(),
            'bookform' => true
        ));
    });

    return $app;
?>
