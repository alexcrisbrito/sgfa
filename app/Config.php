<?php

use Dotenv\Dotenv;

$env = Dotenv::createImmutable(dirname(__DIR__, 1));
$env->load();


define("SITE", [
    "root" => "http://sys.sgfa.local",
    "description" => "Sistema de Gestão de Furos de Água",
    "locale" => "pt_PT",
    "datetime" => "Africa/Maputo",
    "version" => "1.2"
]);

define("BUSINESS_MODEL", [
    "Price" => 70.00,
    "basePrice" => 420.00,
    "baseVolume" => 6.00,
    "send_auto_sms" => false,
    "money_acc" => [
        "Conta Móvel - 846099218",
        "MBIM - 51504576",
        "BCI - 777678210003",
        "Mpesa - 846099218",
        "Dinheiro"
    ],
    "multa" => 200.00
]);

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

define("SMS_API", [
    "apiKey" => "45a981a879a249528770ac90ce7159e8",
    "apiSecret" => "67670373222d417aa393dcc366241c68",
]);
