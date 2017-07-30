SaleStock Backend Dev Test
==========================

Introduction
------------
This is a submission for Salestock Backend Dev test built using Zend Framework 2.
This application acts as webservice endpoints for the requested business scenario (Order Processing).
Application is built using the foundation from [Zend Skeleton Application](GitHub - zendframework_ZendSkeletonApplication_ Skeleton application for zend-mvc projects.html)
The ZF2 version used is 2.4.11.

Showcase
--------
This application is built using Domain Driven Design approach where business logic are made into entities and valueobjects.
e.g. 'Product' is considered as an entity, while an 'order item' is a valueobject. The 'Order' itself is an aggregate root for accessing a collection of 'order items'.

This is a fully functional application where all state changes are persisted to database (Mysql). 
This utilizes ZF2 components such as 'Zend Db' and 'Zend Hydrator'.
The data persistence layer is architectured using the Data Mapper pattern.

Online Version
--------------
An online version has been deployed [here](http://128.199.104.220/~andi/public/).
Refer to the [API doc](APIDOC.md) for details on testing.

Offline Installation
--------------------
**Note**: Testing the application offline enables you to view the changes persisted in the database.
Prequisites:
1. Apache 2 Server.
2. PHP minimum version 5.6.
3. Mysql Database.
4. php-intl extension enabled.
5. PHP Composer, get it from [here](https://getcomposer.org/download/)

Installation instructions:
1. Download or clone source files from repository to your local directory.
2. Configure a new virtual host in your Apache configuration file. Refer to 'Apache Setup' from [here](https://github.com/zendframework/ZendSkeletonApplication/blob/master/README.md)
2. Open your console, navigate to source file location, run command: 'composer install' (this is needed so composer will download required libraries).
3. Create a new database schema in mysql, run the sql commands from file: 'sstest_sql.sql' in the new schema.
4. Navigate to 'config/autoload' folder, replace the mysql database name in 'global.php' and mysql username and password in 'local.php'. Use the same database name from step 2.
5. Set directory 'public' as document root in your web server.

You can now run the application. Refer to the [API doc](APIDOC.md) for details on testing.

Running Unit Test
-----------------
Unit tests is performed by using PHPUnit which will become avaialbe after running 'composer install' during offline installation.
Running unit tests requires console access. You can perform this after following offline installation steps above.
1. Open your console, navigate to application 'test' folder
2. Run PHPUnit using the following command:
e.g. (on Windows):
    `..\vendor\bin\phpunit.bat --debug`

e.g (on Linux):
    `../vendor/bin/./phpunit --debug`
