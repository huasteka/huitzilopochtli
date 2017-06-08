# Huitzilopochtli

[![Travis](https://img.shields.io/travis/murilocosta/tepoztecatl.svg?style=flat-square)](https://travis-ci.org/murilocosta/tepoztecatl)
[![GitHub issues](https://img.shields.io/github/issues/murilocosta/tepoztecatl.svg?style=flat-square)](https://github.com/murilocosta/tepoztecatl/issues)
[![Code Climate](https://img.shields.io/codeclimate/github/murilocosta/tepoztecatl.svg?style=flat-square)](https://codeclimate.com/github/murilocosta/tepoztecatl)
[![Codecov](https://img.shields.io/codecov/c/github/murilocosta/tepoztecatl.svg?style=flat-square)](https://codecov.io/gh/murilocosta/tepoztecatl)

Huitzilopochtli is an open source purchases and sales management API developed with Lumen Framework.

## Setup

- [PostgreSQL](http://www.postgresql.org) (>= 9.3)
- [PHP](http://php.net) (~ 5.6.14)
- [Composer](https://getcomposer.org/) (>= 1.2.0)

## Installation

To download and build the project, open a terminal and execute:

```
git clone https://github.com/murilocosta/huitzilopochtli.git
cd huitzilopochtli
composer install
```

Run the database migrations with the command: `php artisan migrate`.

To serve the project run: `php -S localhost:8000 -t public` the application will be served at `http://localhost:8000`.

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

## Tests

To execute all tests, open a terminal and execute:

```
php vendor/bin/phpunit
```

## License

Huitzilopochtli is Copyright © 2017 Murilo Costa.

It is free software, and may be redistributed under the terms specified in the [LICENSE.md](LICENSE.md)
