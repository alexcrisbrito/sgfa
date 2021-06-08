<?php


namespace App\Models;


use Alexcrisbrito\Php_crud\Crud;

class Statistics extends Crud
{
    public function __construct()
    {
        parent::__construct("statistics", ["date", "profits", "expenses", "consumption"]);
    }
}