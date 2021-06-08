<?php

namespace App\Controllers\Pages;

use App\Controllers\BaseController;
use App\Helpers\SessionManager;

class Pub extends BaseController
{

    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function login(): void
    {
        if(SessionManager::has()) {
            $this->router->redirect("home");
        }
        echo $this->view->render("public::login");
    }

    public function change():void
    {
        if(!SessionManager::has() || !SessionManager::isFirstLogin()){
            $this->router->redirect("pub.login");
        }

        echo $this->view->render("public::change_password");
    }

}