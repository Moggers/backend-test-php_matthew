# REX Forum Backend 


## Dev Environment Setup

* Install composer
* Pull dependencies using `composer install`
* Copy `.env.example to `.env`
* Use `php artisan key:generate --show` to generate a key, copy it to the empty `APP_KEY` field in `.env`.
* Configure passport using `php artisan passport:client`
* `php artisan serve`
