# Functional tests: Behat and Symfony Mailer

## Requirements

* [git](https://git-scm.com/)
* [PHP](https://www.php.net) 7.4
* [Composer](https://getcomposer.org)

Optionally (if you want to run the app):

* [Symfony binary](https://symfony.com/download)
* [Docker](https://www.docker.com/products/docker-desktop)

## Installation

```shell script
git clone https://github.com/rmhdev/testing-emails-bdd.git
cd testing-emails-bdd
composer install
```

## Launch the tests

```shell script
vendor/bin/behat
```

## Play with the demo

You can execute this demo in your local environment; 
be sure **Docker** and the **Symfony binary** are present in your system.

```shell script
docker-compose up -d
symfony serve
```

`docker-compose` will launch a [MailCatcher](https://mailcatcher.me/) service, 
which will allow you to send and receive emails inside your machine. 
The `symfony` command will launch a local web server in an available URL; 
**open that URL in your browser**, fill the fields and click the "send" button.
Now open mailcatcher in your browser and see the email message:

```http request
http://localhost:31080/
```

To end the demo, hit `CTRL + C` to stop the Symfony process and then stop the Docker services with:

```shell script
docker-compose down
```

## Other commands

Static analysis:

```shell script
vendor/bin/phpstan.phar analyse -c phpstan.neon
```

Code styling:

```shell script
vendor/bin/ecs check --config ecs.php
```
