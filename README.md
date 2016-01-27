### A Laravel 5 package to test database migrations before you get into trouble

Please don't use in production environments YET!! We still need some tests && feedback.

#### Why
Mysql does not allow wrapping table operations in transactions (Postgresql does), that's
why when you screw one of your migrations, you are basically left in an irreparable broken
state.

So the main idea behind this package is the following:
- We create a testing database for every connection specified by the user (or use the default one).
- We switch the configuration to the new database at runtime, replacing the real database.
- We refresh the connection so that Laravel can use the new configuration.
- We run ``` php artisan migrate ``` against the testing database.
- When done, we drop the testing database.
- Any eventual errors are displayed to the user.
- The real database stays intact.

#### Sqlite not supported
Sqlite databases are not supported for the time being. If someone has an idea that would be great.

#### Install
``` composer require shincoder/harmless-migration:dev-develop ```

#### Add the service provider
Edit your ``` config/app.php ``` providers array, add the following:
```php
'Shincoder\Harmless\HarmlessServiceProvider',
```

#### Migrate away
If you use multiple database connections you should specify them all using the ``` --database=db1,db2 ``` option.
If not, the command will use the default connection in ``` config/database.php ```.

- Single database connection
``` php artisan migrate:test ```

- Multiple database connections
``` php artisan migrate:test --database=connection1,connection2 ```
