SaleStock Backend Dev Test
==========================

Introduction
------------
This is a submission for Salestock Backend Dev test built using Zend Framework 2.
This application acts as webservice endpoints for the requested business scenario (Order Processing) using HTTP protocol.
Application is built using the foundation from [Zend Skeleton Application](https://github.com/zendframework/ZendSkeletonApplication).
The ZF2 version used is 2.4.11.

Showcase
--------
This application is built using Domain Driven Design approach where business logic are made into entities and valueobjects.
e.g. 'Product' is considered as an entity, while an 'order item' is a valueobject. The 'Order' itself is an aggregate root for accessing a collection of 'order items'.
The domain models are located at '**/module/Emoneygw/src/Emoneygw/Model/Concrete/**' and '**/module/Emoneygw/src/Emoneygw/ValueObject/Concrete/**'

This is a fully functional application where all state changes are persisted to database (Mysql). 
This utilizes ZF2 components such as '**Zend Db**' and '**Zend Hydrator**'.
The data persistence layer is architectured using the Data Mapper pattern.
The data mappers are located at '**/module/Emoneygw/src/Emoneygw/DataMapper/Concrete/**' and **/module/Emoneygw/src/Emoneygw/DataMapper/Hydrator/**'

Dependency Injection is handled by utlizing ZF2's built in '**Zend ServiceManager**' using Factory classes located at '**/module/Emoneygw/src/Emoneygw/Factory/DataMapper/**'

Online Version
--------------
An online version has been deployed [here](http://128.199.104.220/~andi/public/).
Refer to the [API doc](APIDOC.md) for details on testing.

Offline Installation
--------------------
**Note**: Testing the application offline enables you to view the changes persisted in the database after performing the provided API calls.

Prequisites:
1. Apache 2 Server.
2. PHP minimum version 5.6.
3. Mysql Database.
4. php-intl extension enabled. 
5. PHP Composer must be installed on the OS, get it from [here](https://getcomposer.org/download/)

Installation instructions:
1. Download or clone source files from repository to your local directory.
2. Configure a new virtual host in your Apache configuration file. Refer to 'Apache Setup' from [here](https://github.com/zendframework/ZendSkeletonApplication/blob/master/README.md). Set directory '**/public/**' as document root in your web server.
3. Open your console, navigate to source file location, run command: 'composer install' (this is needed so composer will download required libraries and place them in '**/vendor/**' folder).
4. Create a new database schema in mysql, run the sql commands from file: 'sstest_sql.sql' in the new schema.
5. Navigate to '**/config/autoload/**' folder, replace the mysql database name in '**global.php**' and mysql username and password in '**local.php**'. Use the same database name from step 4.

You can now run the application. Refer to the [API doc](APIDOC.md) for details on testing.

Running Unit Test
-----------------
Unit tests are performed by using PHPUnit which will become avaialbe after running '**composer install**' during offline installation.
The test configuration is located at '/tests' and the unit test code is located at 'test' folder under '/module' (e.g. /module/Emoneygw/test/)

Running unit tests requires console access. You can perform this after following offline installation steps above.
1. Open your console, navigate to the '**/test/**' folder
2. Run PHPUnit using the following command:.

e.g. (on Windows):

    ..\vendor\bin\phpunit.bat --debug

e.g (on Linux):

    ../vendor/bin/./phpunit --debug

**Note**: The command tries to execute the PHP Unit located in and parses the '**phpunit.xml**' configuration file in the '**/test/**' folder.
Alternatively you can navigate to the '**/vendor/bin**' folder and run the command and provide the '**phpunit.xml**' file path in the command:

e.g. (on Windows):

    phpunit.bat --debug --configuration \path\to\test\phpunit.xml

e.g (on Linux):

    ./phpunit --debug --configuration \path\to\test\phpunit.xml
