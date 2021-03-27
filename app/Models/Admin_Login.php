<?php


namespace App\Models;


use Alexcrisbrito\Php_crud\Crud;

class Admin_Login extends Crud
{
    public function __construct()
    {
        parent::__construct("admin_login");
    }
}