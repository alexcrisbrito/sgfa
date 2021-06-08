<?php


namespace App\Models;

use Alexcrisbrito\Php_crud\Crud;

class Expense extends Crud
{
    public function __construct()
    {
        parent::__construct("expense", ["name", "amount"]);
    }
}