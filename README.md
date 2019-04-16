dpINVOICER - application for making out invoices
========================

**INFO**: This is a Symfony 3.4 application with a MySQL database

What features are included?
--------------

  * Contractor registry,

  * Product Registry,

  * Invoice registry,

  * CRUD functionalities are served by REST API except for invoice list which is rendered from PHP,
  
  * The frontend features Bootstrap 4 and jQuery,
  
  * The Model is handled by Doctrine and the view uses Twig templating 
  

Installation for development
-----------------------------
* Run "composer install" to install dependencies,
* Create an empty MySQL database,
* Inside the app/config folder copy the parameters.yml.dist file as parameters.yml and provide your database credentials
* run "php bin/console doctrine:schema:update --force" to write all the tables and relations to the database
* run "php bin/console doctrine server:start" to start a built in web server or set up your own domain with virtual host pointing to the web directory


