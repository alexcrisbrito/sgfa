<?php


namespace App\Models;


use Alexcrisbrito\Php_crud\Crud;

class Client_Login extends Crud
{
    public function __construct()
    {
        parent::__construct("client_login");
    }
}