<?php


namespace App\Models;


use Alexcrisbrito\Php_crud\Crud;

class Transactions extends Crud
{
    public function __construct()
    {
        parent::__construct("transactions");
    }
}