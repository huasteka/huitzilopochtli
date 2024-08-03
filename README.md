# Huitzilopochtli

[![CircleCI](https://dl.circleci.com/status-badge/img/gh/huasteka/huitzilopochtli/tree/master.svg?style=svg)](https://dl.circleci.com/status-badge/redirect/gh/huasteka/huitzilopochtli/tree/master)
[![Maintainability](https://api.codeclimate.com/v1/badges/685e5d7541593ab60ed6/maintainability)](https://codeclimate.com/github/huasteka/huitzilopochtli/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/685e5d7541593ab60ed6/test_coverage)](https://codeclimate.com/github/huasteka/huitzilopochtli/test_coverage)

Huitzilopochtli is an open source purchases and sales management API developed with [Lumen Framework](https://lumen.laravel.com).

## Setup

- [PostgreSQL](http://www.postgresql.org) (>= 9.3)
- [PHP](http://php.net) (~ 7.4)
- [Composer](https://getcomposer.org/) (~ 2.7.7)

## Installation

To download and build the project, open a terminal and execute:

```sh
git clone https://github.com/huasteka/huitzilopochtli.git
cd huitzilopochtli
composer install
```

Run the database migrations with the command: `php artisan migrate`.

To serve the project run: `php -S localhost:9701 -t public` the application will be served at `http://localhost:9701`.

## Configuration

To configure application rename the file `.env.example` to `.env`, and customize your environment:

```
DB_CONNECTION=[database_driver]
DB_HOST=[database_host]
DB_PORT=[database_port]
DB_DATABASE=[database_name]
DB_USERNAME=[database_user]
DB_PASSWORD=[database_password]
```

To run the database migrations, open a terminal and execute:

```sh
php artisan migrate
```

## Tests

To execute all tests, open a terminal and execute:

```sh
php vendor/bin/phpunit
```

## Run

To run the application, open a terminal and execute:

```sh
php artisan serve
```

## License

Huitzilopochtli is Copyright © 2017 Huasteka.

It is free software, and may be redistributed under the terms specified in the [LICENSE.md](LICENSE.md)
