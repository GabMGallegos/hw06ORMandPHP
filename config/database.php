<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use RedBeanPHP\R;

try {
    /*
        En local, este código lee el archivo .env.
        En Render, el archivo .env no existe, pero las variables se cargan
        desde Environment Variables. Por eso se usa safeLoad().
    */
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();

    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: '';
    $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '';
    $dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: '';
    $user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: '';
    $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?: '';

    if ($host === '' || $port === '' || $dbname === '' || $user === '' || $password === '') {
        die('Missing environment values. Check DB_HOST, DB_PORT, DB_NAME, DB_USER and DB_PASSWORD.');
    }

    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";

    R::setup($dsn, $user, $password);

    if (!R::testConnection()) {
        die('Database connection failed.');
    }

    R::freeze(true);
} catch (Throwable $e) {
    die('Connection error: ' . $e->getMessage());
}