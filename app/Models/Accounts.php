<?php


namespace App\Models;


use Alexcrisbrito\Php_crud\Crud;

class Accounts extends Crud
{

    public function __construct()
    {
        parent::__construct("accounts", ['name']);
    }

}