<?php


namespace App\Installer\Model;


use Alexcrisbrito\Php_crud\Crud;

class Admin_Login extends Crud
{
    public function __construct()
    {
        parent::__construct("admin_login");
    }
}