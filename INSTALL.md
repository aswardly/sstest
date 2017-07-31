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
