<?php

namespace App\Models;

use Alexcrisbrito\Php_crud\Crud;

class Receipt extends Crud
{
    public function __construct()
    {
        parent::__construct("receipt", []);
    }
}