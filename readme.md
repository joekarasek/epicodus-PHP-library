# _Library App_

#### _Last PHP and SQL in-class exercise for Epicodus PHP, 3.3.2016_

### By _**Joseph Karasek and Mary Warrington**_

## Description

_This web app is designed to work for a library. The app can be used as a librarian or patron. Librarians are able to add books, add copies of that book to the libraries catalog, see where those copies are, update book info, and delete copies. Patrons can see their checkouts, checkout books, and return books._

_One incomplete component is the handling of due-dates. Dates are currently hard coded to always be due 2-14-2016. This functionality maybe built out in the future._

## Setup/Installation Requirements

### Setup of databases

In terminal run the following commands to setup the database:

    apachectl start
    mysql.server start
    mysql -uroot -proot

1. _Fork and clone this repository from_ [gitHub](https://github.com/joekarasek/epicodus-PHP-library.git).
2. Navigate to the root directory of the project in which ever CLI shell you are using and run the command: __composer install__.
3. Create a local server in the /web directory within the project folder using the command: __php -S localhost:8000__ (assuming you are using a mac).
4. Open the directory http://localhost:8000 in any standard web browser.
5. Open http://localhost:8080/phpmyadmin and import ../library.sql.zip.

## Known Bugs

_There are no known bugs at this time. This app is not fully developed at this point and some functionality is either missing or not clear._

## Support and contact details

_If you have any questions, concerns, or feedback, please contact the author through_ [gitHub](https://github.com/joekarasek/epicodus-PHP-library.git).

## Technologies Used

_This web application was created using the_  [Silex micro-framework](http://silex.sensiolabs.org/)_, as well _[Twig](http://twig.sensiolabs.org/), _a template engine for php, and_ [mySQL](https://www.mysql.com/).

### License

MIT License.

Copyright (c) 2016 **_Joseph Karasek and Mary Warrington_**
