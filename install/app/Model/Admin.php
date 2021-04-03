<?php


namespace App\Installer\Model;


use Alexcrisbrito\Php_crud\Crud;

class Admin extends Crud
{
    public function __construct()
    {
        parent::__construct("admin");
    }
}