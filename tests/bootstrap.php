<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(
    file_exists(dirname(__DIR__).'/.env.test.local')
        ? dirname(__DIR__).'/.env.test.local'
        : dirname(__DIR__).'/.env.test'
);
