<?php

use Dotenv\Dotenv;

$env = Dotenv::createImmutable(dirname(__DIR__, 1));
$env->load();

const SITE = [
    "root" => "http://sys.sgfa.local",
    "description" => "Sistema de Gestão de Furos de Água",
    "version" => "1.2"
];

const BUSINESS_MODEL = [
    "send_auto_sms" => false,
];

define("DB_CONFIG", [
    "driver" => "mysql",
    "host" => $_ENV["DB_HOST"],
    "port" => $_ENV["DB_PORT"],
    "dbname" => $_ENV["DB_NAME"],
    "username" => $_ENV["DB_USER"],
    "passwd" => $_ENV["DB_PASS"],
    "options" => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_PERSISTENT => true
    ]
]);

const SMS_API = [
    "apiKey" => "45a981a879a249528770ac90ce7159e8",
    "apiSecret" => "67670373222d417aa393dcc366241c68",
];
