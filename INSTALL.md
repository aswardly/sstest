SaleStock Backend Dev Test
==========================

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

The virtual host entry should be similiar to the following: 

```apache
<VirtualHost *:80>
    ServerName sstest.localhost
    DocumentRoot /path/to/public
    <Directory /path/to/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

3. Open your console, navigate to source file location, run command: 'composer install' (this is needed so composer will download required libraries and place them in '**/vendor/**' folder).
4. Create a new database schema in mysql, run the sql commands from file: 'sstest_sql.sql' in the new schema.
5. Navigate to '**/config/autoload/**' folder, replace the mysql database name in '**global.php**' and mysql username and password in '**local.php**'. Use the same database name from step 4.

You can now run the application. Refer to the [README doc](README.md) for details on testing.

Running Unit Test
-----------------
Unit tests are performed by using PHPUnit which will become available after running '**composer install**' during offline installation.
The test configuration is located at '/tests' and the unit test code is located at 'test' folder under '/module' (e.g. */module/Emoneygw/test/*)

Running unit tests requires console access. You can perform this after following offline installation steps above.
1. Open your console, navigate to the '**/test/**' folder
2. Run PHPUnit using the following command:.

e.g. (on Windows):

    ..\vendor\bin\phpunit.bat --debug

e.g (on Linux):

    ../vendor/bin/./phpunit --debug

**Note**: The command will try to execute PHPUnit located in '*/vendor/bin/*' folder and parses the '**phpunit.xml**' configuration file in the '**/test/**' folder.
Alternatively you can navigate to the '**/vendor/bin**' folder and run the command and provide the '**phpunit.xml**' file path in the command:

e.g. (on Windows):

    phpunit.bat --debug --configuration \path\to\test\phpunit.xml

e.g (on Linux):

    ./phpunit --debug --configuration \path\to\test\phpunit.xml

After the unit tests is run you will get a result similiar to the following screenshot:

![Alt text](unit_test_capture.JPG?raw=true "Sample unit test run results")