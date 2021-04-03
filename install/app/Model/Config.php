<?php


namespace App\Installer\Model;


use Alexcrisbrito\Php_crud\Crud;

class Config extends Crud
{
    public function __construct()
    {
        parent::__construct("config");
    }
}