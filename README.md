# MCAPI

This repository contains a RESTful API based on Modus Create requirements in the file `php-api-assignment.md`. Know that this application uses Lumen as microframework and a couple of libraries like Guzzle, Mockery and CodeSniffer.

**How did I do it?**

The approach taken in this project is based on TDD development workflow. Basically, I tried to fulfill the requirements by creating unit tests, this allowed me to build the implementation and refactor as needed.

The unit tests are divided in two directories; `Feature` and `Unit`. The `Feature` directory has tests that checks the desired behavior, aka. Integrations Tests. The `Unit` directory evaluates just contains test that checks every class and it's methods in order to guarantee functionality.

## Requirements

1. PHP >= 7.1.3
2. OpenSSL PHP Extension
3. PDO PHP Extension
4. Mbstring PHP Extension
5. Composer

## Setup

1. Create an `.env` file base on the `.env.example` in this repo. Notice the `API_URL` key, it's very important.
2. Run `composer install` to install dependencies.

## Serving

The simples way to run the application is by simply tipyng the follwing in your terminal at the root the project:

```bash
php -S localhost:8000 -t public
```

## Extra

### Run Unit Tests

```bash
./vendor/bin/phpunit
```

### Run Code Sniffer

```bash
./vendor/bin/phpcs .
```

## Troubleshoot

1. If for some reason, you get an namespace error when running the application, try with this command first `composer dump-autoload -o`.
2. Send an email to `nosnibor1989@gmail.com`.