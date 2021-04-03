<?php


namespace App\Installer\Controllers;


use CoffeeCode\Router\Router;
use League\Plates\Engine;

class Nav
{
    private $router;
    private $renderer;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->renderer = new Engine(dirname(__DIR__). "/Views");
        $this->renderer->addData(["router" => $router]);
    }

    public function index()
    {
        echo $this->renderer->render("index");
    }

    public function users()
    {
        echo $this->renderer->render("users");
    }

    public function database()
    {
        echo $this->renderer->render("config");
    }

    public function thanks()
    {
        echo $this->renderer->render("thanks");
    }
}