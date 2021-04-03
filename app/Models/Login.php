<?php


namespace App\Models;

use Alexcrisbrito\Php_crud\Crud;

class Login extends Crud
{

    public function __construct()
    {
        parent::__construct("login");
    }

}