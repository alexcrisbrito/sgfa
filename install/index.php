<?php

use Dotenv\Dotenv;
use CoffeeCode\Router\Router;

session_start();
ob_start();

require '../vendor/autoload.php';

$dotenv = Dotenv::createMutable(dirname(__DIR__));
$dotenv->load();

$router = new Router(site()."/install");
$router->namespace("App\Installer\Controllers");

$router->get("/", "Nav:index", "pub.index");
$router->get("/administrador", "Nav:users", "pub.users");
$router->get("/configurar", "Nav:database", "pub.config");
$router->get("/finalizado", "Nav:thanks", "pub.thanks");

$router->post("/activate", "Form:index", "form.index");
$router->post("/admin", "Form:admin", "form.admin");
$router->post("/setup", "Form:config", "form.setup");

$router->dispatch();

ob_end_flush();


