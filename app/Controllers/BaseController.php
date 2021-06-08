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

        $this->view->addData(["router" => $this->router]);

        /* Let's check if the system is already installed */
        if ($_ENV['APP_STATE'] == "INSTALL") {
            header("Location: /install");
        }
    }

    protected function jsonResult($result, $message = "", $data = []) {
        echo json_encode(compact("result", "message", "data"));
    }
}