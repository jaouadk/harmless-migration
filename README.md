### A Laravel 5 package to test database migrations before you get into trouble

#### Install
``` composer require shincoder/harmless-migration:dev-develop ```

#### Add service provider
```php
'Shincoder\Harmless\HarmlessServiceProvider'
```

#### Migrate away

- Single database connection
``` php artisan migrate:test ```

- Multiple database connections
``` php artisan migrate:test --database=connection1,connection2 ```
