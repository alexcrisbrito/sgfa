<?php


namespace App\Controllers;


use CoffeeCode\Router\Router;
use League\Plates\Engine;

abstract class BaseController
{

    /** @var Engine */
    protected $view;

    /** @var Router */
    protected $router;

    public function __construct($router)
    {
        $this->router = $router;
        $this->view = new Engine();
        $this->view->setFileExtension("php");
        $this->view->addFolder("admin",dirname(__DIR__, 1) . "/Views/admin");
        $this->view->addFolder("worker",dirname(__DIR__, 1) . "/Views/worker");
        $this->view->addFolder("user",dirname(__DIR__, 1) . "/Views/user");
        $this->view->addFolder("public",dirname(__DIR__, 1) . "/Views/public");
        $this->view->addFolder("docs",dirname(__DIR__, 1) . "/Views/docs");
        $this->view->addFolder("other",dirname(__DIR__, 1) . "/Views/other");

        $this->view->addData(["router" => $this->router]);
    }
}