### A Laravel 5 package to test database migrations

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
``` composer require shincoder/harmless-migration:dev-master ```

#### Add the service provider
Edit your ``` config/app.php ``` providers array, add the following:
```php
'Shincoder\Harmless\HarmlessServiceProvider',
```

#### Migrate away
The package has no way to guess which connections you are using.
So if you use multiple database connections you **Must** specify them all using the ``` --database=db1,db2 ``` option.
If not, the command will use the default connection in ``` config/database.php ```.

If you don't specify all your connections, some of you migrations will still be run against your real database.
This shouldn't be an issue if you only use your default connection.

- Default database connection
``` php artisan migrate:test ```

- Multiple database connections
``` php artisan migrate:test --database=defaultConnection1,connection2 ```
