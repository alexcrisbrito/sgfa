<?php


namespace App\Models;

use alexcrisbrito\php_crud\Crud;

class Login extends Crud
{

    public function __construct()
    {
        parent::__construct("login");
    }

}