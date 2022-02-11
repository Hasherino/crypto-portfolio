# Crypto portfolio

## To launch the API on your local machine:
* Launch a local MySQL database and create `.env` file based on `.env.example` and edit it to match your MYSQL database configuration
* `composer update`
* `php artisan jwt:secret`
* `php artisan key:generate`
* `php artisan migrate`
* `php artisan DB:seed`
* `php artisan serve`
