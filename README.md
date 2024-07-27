# Huitzilopochtli

[![Travis](https://img.shields.io/travis/huasteka/huitzilopochtli.svg?style=flat-square)](https://travis-ci.org/huasteka/huitzilopochtli)
[![GitHub issues](https://img.shields.io/github/issues/huasteka/huitzilopochtli.svg?style=flat-square)](https://github.com/huasteka/huitzilopochtli/issues)
[![Maintainability](https://api.codeclimate.com/v1/badges/53d7c83ee31c34096261/maintainability)](https://codeclimate.com/github/huasteka/huitzilopochtli/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/53d7c83ee31c34096261/test_coverage)](https://codeclimate.com/github/huasteka/huitzilopochtli/test_coverage)
[![Codecov](https://img.shields.io/codecov/c/github/huasteka/huitzilopochtli.svg?style=flat-square)](https://codecov.io/gh/huasteka/huitzilopochtli)

Huitzilopochtli is an open source purchases and sales management API developed with [Lumen Framework](https://lumen.laravel.com).

## Setup

- [PostgreSQL](http://www.postgresql.org) (>= 9.3)
- [PHP](http://php.net) (~ 5.6.14)
- [Composer](https://getcomposer.org/) (>= 1.2.0)

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
