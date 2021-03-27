<?php


namespace App\Models;


use Alexcrisbrito\Php_crud\Crud;

class Config extends Crud
{
    public function __construct()
    {
        parent::__construct("config");
    }
}