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

    return $app;
?>
