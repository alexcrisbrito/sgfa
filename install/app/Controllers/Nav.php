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
        $this->renderer = new Engine(dirname(__DIR__) . "/Views");
        $this->renderer->addData(["router" => $router]);
        $this->renderer->registerFunction("show_alert", function () {
            if (isset($_SESSION['alert']) && $alert = $_SESSION['alert']) {
                unset($_SESSION['alert']);

                return '<div class="alert alert-'. $alert['type'] .' alert-dismissible fade show"  id="callback">
                       <button type="button" class="close" onclick="hide()">
                            <span>&times;</span>
                       </button>
                       '. $alert['msg'] .'
                </div>';
            }
        });

        /* Let's check if the system is already installed */
        if ($_ENV['APP_STATE'] == "PROD") {
            header("Location: /");
        }
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