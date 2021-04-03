<?php


namespace App\Models;

use Alexcrisbrito\Php_crud\Crud;

class Expenses extends Crud
{
    public function __construct()
    {
        parent::__construct("expenses", ["name", "amount"]);
    }
}