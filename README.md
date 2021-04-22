## Running Tests ##


1) Create a database named "test_ecom"

2) Copy and paste the following to your .env and fill out with correct data

```
#!terminal

DB_TEST_HOST=localhost
DB_TEST_PORT=3306
DB_TEST_DATABASE=test_ecom
DB_TEST_USERNAME=
DB_TEST_PASSWORD=
```


3) Run the following commands in terminal


```
#!terminal

composer dump-autoload
php artisan clear-compiled
php artisan config:clear  
php artisan cache:clear
php artisan migrate:refresh --database=testing
php artisan db:seed --database=testing
php artisan passport:install
./vendor/bin/phpunit
```
