<?php

namespace App\Models;

use alexcrisbrito\php_crud\Crud;

class Receipt extends Crud
{
    public function __construct()
    {
        parent::__construct("receipt", []);
    }
}